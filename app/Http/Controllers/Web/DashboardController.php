<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Pure Web MVC — no API, Eloquent + Blade
        $query = Registration::query();

        // Optional council scoping (if user session has council and not VP)
        $sessionUser = $request->session()->get('user');
        $userCouncil = $sessionUser['council'] ?? null;
        $userRole = $sessionUser['role'] ?? null;
        if ($userRole !== 'VP' && $userRole !== 'President' && !empty($userCouncil)) {
            $query->where('council', $userCouncil);
        }

        if ($request->filled('search')) {
            $s = '%'.$request->search.'%';
            $query->where(function($q) use ($s){ $q->where('name','like',$s)->orWhere('email','like',$s); });
        }
        if ($request->filled('level')) $query->where('level','like','%'.$request->level.'%');
        if ($request->filled('rating')) $query->where('rating','like','%'.$request->rating.'%');
        if ($request->filled('event_type')) $query->where('event_type','like','%'.$request->event_type.'%');
        if ($request->filled('council')) $query->where('council','like','%'.$request->council.'%');

        $statsQuery = clone $query;
        $stats = [
            'total' => (clone $statsQuery)->count(),
            'accepted' => (clone $statsQuery)->where('rating','Acceptance')->count(),
            'backup' => (clone $statsQuery)->where('rating','B')->count(),
            'rejected' => (clone $statsQuery)->where('rating','Rejection')->count(),
            'pending' => (clone $statsQuery)->where(function($q){ $q->whereNull('rating')->orWhere('rating','Pending'); })->count(),
        ];

        $applicants = $query->orderBy('id','desc')->paginate(20)->withQueryString();

        return view('dashboard.index', compact('stats','applicants'));
    }

    public function calendar()
    {
        $interviews = Registration::whereNotNull('interview_time')->orderBy('interview_time')->get();
        return view('dashboard.calendar', compact('interviews'));
    }

    public function statistics()
    {
        $applicants = Registration::all();
        return view('dashboard.statistics', compact('applicants'));
    }

    public function usherStats()
    {
        $stats = Registration::selectRaw('ushered_by as name, COUNT(*) as count')
            ->whereNotNull('ushered_by')
            ->whereNotIn('ushered_by', ['', 'NA', 'N/A', 'na', 'n/a', 'none', 'null', 'None'])
            ->groupBy('ushered_by')
            ->orderByDesc('count')
            ->get();

        $totalApplicants = Registration::count();
        $activeUshers = $stats->count();
        $avgPerUsher = $activeUshers ? round($totalApplicants / $activeUshers, 1) : 0;

        return view('dashboard.usher-stats', compact('stats', 'totalApplicants', 'activeUshers', 'avgPerUsher'));
    }

    public function interviewerStats()
    {
        $excluded = ['', 'NA', 'N/A', 'na', 'n/a', 'none', 'null', 'None'];
        // ONLY system users – fixes "names not exists in my systems" (e.g. alda.walter)
        $systemUsernames = User::pluck('username')->all();
        // Overall leaderboard (group by interviewed_by) – from interviewed_by, filtered to existing users
        $overallQuery = Registration::selectRaw('interviewed_by as name, COUNT(*) as count')
            ->whereNotNull('interviewed_by')
            ->whereNotIn('interviewed_by', $excluded);
        if (!empty($systemUsernames)) {
            $overallQuery->whereIn('interviewed_by', $systemUsernames);
        }
        $overall = $overallQuery->groupBy('interviewed_by')->orderByDesc('count')->get();

        // Per-council breakdown – also only system users
        $detailedQuery = Registration::selectRaw('interviewed_by as name, council, COUNT(*) as count')
            ->whereNotNull('interviewed_by')
            ->whereNotIn('interviewed_by', $excluded);
        if (!empty($systemUsernames)) {
            $detailedQuery->whereIn('interviewed_by', $systemUsernames);
        }
        $detailed = $detailedQuery->groupBy('interviewed_by', 'council')->orderByDesc('count')->get();

        $perCouncil = $detailed->groupBy('council');

        // Council list
        $councils = Registration::distinct()->whereNotNull('council')->where('council','!=','')->pluck('council')->sort()->values();
        // Also include councils from Council model
        $councilModels = \App\Models\Council::pluck('name');
        $councils = $councils->merge($councilModels)->unique()->sort()->values();

        // Map username -> user (role, council)
        $usersMap = User::all()->keyBy('username');

        // Split by role: instructors vs heads/vp
        $instructorOverall = $overall->filter(function($row) use ($usersMap){
            $u = $usersMap->get($row->name);
            return $u && $u->role === 'Instructor';
        })->values();
        $headOverall = $overall->filter(function($row) use ($usersMap){
            $u = $usersMap->get($row->name);
            return $u && in_array($u->role, ['Head','VP','President']);
        })->values();
        $otherOverall = $overall->filter(function($row) use ($usersMap){
            return !$usersMap->has($row->name);
        })->values();

        $totalQuery = Registration::whereNotNull('interviewed_by')->whereNotIn('interviewed_by', $excluded);
        if (!empty($systemUsernames)) $totalQuery->whereIn('interviewed_by', $systemUsernames);
        $totalInterviews = $totalQuery->count();
        $activeInterviewers = $overall->count();
        $avgPer = $activeInterviewers ? round($totalInterviews / $activeInterviewers, 1) : 0;

        // Per-council instructor stats
        $perCouncilInstructor = $detailed->filter(function($row) use ($usersMap){
            $u = $usersMap->get($row->name);
            return $u && $u->role === 'Instructor';
        })->groupBy('council');

        // Full instructor roster with counts (even 0) – so "who instructor made how much" is always visible
        $allInstructors = User::where('role','Instructor')->get();
        $instructorRoster = $allInstructors->map(function($user) use ($overall, $detailed) {
            $overallRow = $overall->firstWhere('name', $user->username);
            $count = $overallRow ? (int)$overallRow->count : 0;
            $byCouncil = $detailed->where('name', $user->username)->groupBy('council')->map->sum('count');
            return [
                'username' => $user->username,
                'council' => $user->council,
                'count' => $count,
                'byCouncil' => $byCouncil,
            ];
        })->sortByDesc('count')->values();

        return view('dashboard.interviewer-stats', compact(
            'overall','detailed','perCouncil','perCouncilInstructor','instructorOverall','headOverall','otherOverall',
            'councils','usersMap','totalInterviews','activeInterviewers','avgPer','instructorRoster'
        ));
    }
}
