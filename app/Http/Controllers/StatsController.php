<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    private function getAuth(Request $request)
    {
        $user = $request->attributes->get('auth_user');
        $role = is_object($user) ? ($user->role ?? '') : ($user['role'] ?? '');
        $council = is_object($user) ? ($user->council ?? null) : ($user['council'] ?? null);
        $username = is_object($user) ? ($user->username ?? '') : ($user['username'] ?? '');
        return [$user, $role, $council, $username];
    }

    // Top ushers: who brought most applicants (ushered_by)
    public function ushers(Request $request)
    {
        [$user, $role, $council] = $this->getAuth($request);
        $cacheKey = 'stats_ushers_'.($council??'all').'_'.md5(json_encode($request->query()));
        if (Cache::has($cacheKey)) {
            return response()->json(['status'=>'success','message'=>'Ushers stats retrieved (cached)','data'=>Cache::get($cacheKey)]);
        }

        $query = Registration::whereNotNull('ushered_by')->where('ushered_by','!=','');

        // Council filter: if not VP, restrict to user's council
        if ($role !== 'VP' && $role !== 'President' && !empty($council)) {
            $query->where('council', $council);
        }
        // Explicit council filter from query
        if ($request->filled('council')) {
            $query->where('council', $request->query('council'));
        }

        // Only Head/VP/President ushers? If requested, filter to those roles
        // We do left join to users to check role, but keep all if no match (legacy data has free text)
        $filterRoles = ['Head','VP','President'];
        if ($request->boolean('leaders_only')) {
            $query->whereIn('ushered_by', function($q) use ($filterRoles) {
                $q->select('username')->from('users')->whereIn('role', $filterRoles);
            });
        }

        $stats = $query->select('ushered_by as name', DB::raw('COUNT(*) as count'))
            ->groupBy('ushered_by')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        // Also get per-council breakdown if VP
        $perCouncil = [];
        if ($role === 'VP' || $role === 'President' || $request->filled('per_council')) {
            $perCouncilQuery = Registration::whereNotNull('ushered_by')->where('ushered_by','!=','');
            if ($request->filled('council')) $perCouncilQuery->where('council', $request->query('council'));
            $perCouncil = $perCouncilQuery->select('council','ushered_by as name', DB::raw('COUNT(*) as count'))
                ->groupBy('council','ushered_by')
                ->orderBy('council')->orderByDesc('count')
                ->get()->groupBy('council');
        }

        $total = Registration::whereNotNull('ushered_by')->where('ushered_by','!=','')->count();
        $activeUshers = $stats->count();
        $avg = $activeUshers ? round($total / $activeUshers, 1) : 0;

        $data = [
            'total_applicants' => $total,
            'active_ushers' => $activeUshers,
            'average_per_usher' => $avg,
            'data' => $stats,
            'per_council' => $perCouncil,
        ];

        Cache::put($cacheKey, $data, 30);
        return response()->json(['status'=>'success','message'=>'Ushers stats retrieved','data'=>$data]);
    }

    // Top interviewers: who did most interviews (interviewed_by)
    public function interviewers(Request $request)
    {
        [$user, $role, $council] = $this->getAuth($request);
        $cacheKey = 'stats_interviewers_'.($council??'all').'_'.md5(json_encode($request->query()));
        if (Cache::has($cacheKey)) {
            return response()->json(['status'=>'success','message'=>'Interviewers stats retrieved (cached)','data'=>Cache::get($cacheKey)]);
        }

        $query = Registration::whereNotNull('interviewed_by')->where('interviewed_by','!=','')->where('interviewed_by','!=','');

        // Council filter: if not VP, restrict
        if ($role !== 'VP' && $role !== 'President' && !empty($council)) {
            $query->where('council', $council);
        }
        if ($request->filled('council')) {
            $query->where('council', $request->query('council'));
        }

        // Exclude Instructors from interviewers leaderboard (per requirement: instructor not appear)
        // Filter to Head/VP/President only
        $allowedRoles = ['Head','VP','President'];
        $query->whereIn('interviewed_by', function($q) use ($allowedRoles) {
            $q->select('username')->from('users')->whereIn('role', $allowedRoles);
        });

        $stats = $query->select('interviewed_by as name', 'council', DB::raw('COUNT(*) as count'))
            ->groupBy('interviewed_by','council')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        // Overall without council grouping
        $overall = Registration::whereNotNull('interviewed_by')->where('interviewed_by','!=','')
            ->whereIn('interviewed_by', function($q) use ($allowedRoles) {
                $q->select('username')->from('users')->whereIn('role', $allowedRoles);
            })
            ->select('interviewed_by as name', DB::raw('COUNT(*) as count'))
            ->groupBy('interviewed_by')
            ->orderByDesc('count')
            ->limit(20)
            ->get();

        // Per-council breakdown
        $perCouncil = $query->select('council','interviewed_by as name', DB::raw('COUNT(*) as count'))
            ->groupBy('council','interviewed_by')
            ->orderBy('council')->orderByDesc('count')
            ->get()->groupBy('council');

        $totalInterviews = Registration::whereNotNull('interviewed_by')->where('interviewed_by','!=','')->count();

        $data = [
            'total_interviews' => $totalInterviews,
            'data' => $overall,
            'detailed' => $stats,
            'per_council' => $perCouncil,
        ];

        Cache::put($cacheKey, $data, 30);
        return response()->json(['status'=>'success','message'=>'Interviewers stats retrieved','data'=>$data]);
    }
}
