<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Services\CouncilQuestionsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApplicantController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'college' => 'required|string|max:255',
            'level' => 'required|string|max:50',
            'event_type' => 'nullable|string|max:100',
            'council' => 'nullable|string|max:100',
            'ushered_by' => 'nullable|string|max:255',
        ]);

        try {
            Registration::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'college' => $request->input('college'),
                'level' => $request->input('level'),
                'event_type' => $request->input('event_type'),
                'council' => $request->input('council'),
                'ushered_by' => $request->input('ushered_by'),
            ]);

            Cache::flush();

            return response()->json(['status' => 'success', 'message' => 'Registration submitted!'], 201);
        } catch (\Exception $e) {
            \Log::error('Registration failed', ['input' => $request->all(), 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function index(Request $request)
    {
        $user = $request->attributes->get('auth_user');
        $council = is_object($user) ? ($user->council ?? '') : ($user['council'] ?? '');
        $role = is_object($user) ? ($user->role ?? '') : ($user['role'] ?? '');
        $cachePrefix = $council !== '' ? $council : $role;

        // Quick stats
        if ($request->has('quickstats')) {
            return $this->quickStats($request, $council, $cachePrefix);
        }

        // Interviews
        if ($request->has('interviews')) {
            return $this->interviews($request, $council, $role, $cachePrefix);
        }

        // Applicants listing (cursor pagination)
        return $this->listing($request, $council, $cachePrefix);
    }

    private function quickStats(Request $request, string $council, string $cachePrefix)
    {
        $cacheKey = 'quickstats_'.$cachePrefix.'_'.md5(json_encode($request->query()));
        if (Cache::has($cacheKey)) {
            return response()->json(['status' => 'success', 'message' => 'Quick stats retrieved (cached)', 'data' => Cache::get($cacheKey)]);
        }

        $query = Registration::query();
        if (!empty($council)) {
            $query->where('council', $council);
        }
        if ($request->filled('level')) {
            $query->where('level', 'like', '%'.$request->query('level').'%');
        }
        if ($request->filled('council')) {
            $query->where('council', 'like', '%'.$request->query('council').'%');
        }

        $total = (clone $query)->count();
        $accepted = (clone $query)->where('rating', 'Acceptance')->count();
        $backup = (clone $query)->where('rating', 'B')->count();
        $rejected = (clone $query)->where('rating', 'Rejection')->count();
        $pending = (clone $query)->where(function ($q) {
            $q->whereNull('rating')->orWhere('rating', 'Pending');
        })->count();

        $data = [
            'total' => $total,
            'accepted' => $accepted,
            'backup' => $backup,
            'rejected' => $rejected,
            'pending' => $pending,
        ];

        Cache::put($cacheKey, $data, 15);

        return response()->json(['status' => 'success', 'message' => 'Quick stats retrieved', 'data' => $data]);
    }

    private function interviews(Request $request, string $council, string $role, string $cachePrefix)
    {
        $cacheKey = 'interviews_'.$cachePrefix.'_'.md5(json_encode($request->query()));
        if (Cache::has($cacheKey)) {
            return response()->json(['status' => 'success', 'message' => 'Interviews retrieved (cached)', 'data' => Cache::get($cacheKey)]);
        }

        $query = Registration::whereNotNull('interview_time');
        if ($role !== 'VP' && !empty($council)) {
            $query->where('council', $council);
        }
        $interviews = $query->orderBy('interview_time', 'asc')
            ->get(['id','name','interview_time','council','rating']);

        $data = ['interviews' => $interviews];
        Cache::put($cacheKey, $data, 15);

        return response()->json(['status' => 'success', 'message' => 'Interviews retrieved', 'data' => $data]);
    }

    private function listing(Request $request, string $council, string $cachePrefix)
    {
        $limit = $request->integer('limit', 20);
        $cursor = $request->query('cursor') !== null ? (int) $request->query('cursor') : null;
        $prevCursor = $request->query('prev_cursor') !== null ? (int) $request->query('prev_cursor') : null;

        $filterHash = md5(json_encode($request->query()));
        $cacheKey = "applicants_{$cachePrefix}_cursor{$cursor}_limit{$limit}_{$filterHash}";
        if (Cache::has($cacheKey)) {
            return response()->json(['status' => 'success', 'message' => 'Applicants retrieved (cached)', 'data' => Cache::get($cacheKey)]);
        }

        $query = Registration::query();

        if (!empty($council)) {
            $query->where('council', $council);
        }
        if ($request->filled('id')) {
            $query->where('id', (int) $request->query('id'));
        }
        if ($request->filled('level')) {
            $query->where('level', 'like', '%'.$request->query('level').'%');
        }
        if ($request->filled('rating')) {
            $query->where('rating', 'like', '%'.$request->query('rating').'%');
        }
        if ($request->filled('search')) {
            $search = '%'.$request->query('search').'%';
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)->orWhere('email', 'like', $search);
            });
        }
        if ($cursor !== null) {
            $query->where('id', '<', $cursor);
        }
        if ($prevCursor !== null) {
            $query->where('id', '>', $prevCursor);
        }

        $totalItems = (clone $query)->count();

        $data = (clone $query)->orderBy('id', 'desc')->limit($limit + 1)->get();

        $hasMore = $data->count() > $limit;
        if ($hasMore) {
            $data->pop();
        }

        $nextCursor = $prevCursorVal = null;
        if ($data->isNotEmpty()) {
            $nextCursor = $data->last()->id;
            $prevCursorVal = $data->first()->id;
        }

        // Decode interview_questions and fallback to council questions; handle interview_notes
        $applicants = $data->map(function ($applicant) {
            $arr = $applicant->toArray();
            if (!empty($arr['interview_questions'])) {
                $decoded = is_string($arr['interview_questions']) ? json_decode($arr['interview_questions'], true) : $arr['interview_questions'];
                // If decoding fails, keep original
                $arr['interview_questions'] = $decoded ?? $arr['interview_questions'];
                if (!is_array($arr['interview_questions'])) {
                    $arr['interview_questions'] = CouncilQuestionsService::getAllForCouncil($applicant->council ?? 'Academic Council');
                }
            } else {
                $arr['interview_questions'] = CouncilQuestionsService::getAllForCouncil($applicant->council ?? 'Academic Council');
            }
            // Ensure interview_notes is decoded
            if (isset($arr['interview_notes']) && is_string($arr['interview_notes'])) {
                $arr['interview_notes'] = json_decode($arr['interview_notes'], true);
            }
            return $arr;
        })->values()->all();

        $resp = [
            'applicants' => $applicants,
            'limit' => $limit,
            'total_items' => $totalItems,
            'has_more' => $hasMore,
            'next_cursor' => $hasMore ? $nextCursor : null,
            'prev_cursor' => $cursor ? $prevCursorVal : null,
        ];

        Cache::put($cacheKey, $resp, 30);

        return response()->json(['status' => 'success', 'message' => 'Applicants retrieved', 'data' => $resp]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->attributes->get('auth_user');
        $role = is_object($user) ? ($user->role ?? '') : ($user['role'] ?? '');
        $userCouncil = is_object($user) ? ($user->council ?? null) : ($user['council'] ?? null);
        $username = is_object($user) ? ($user->username ?? '') : ($user['username'] ?? '');

        $applicant = Registration::find($id);
        if (!$applicant) {
            return response()->json(['status' => 'error', 'message' => 'Applicant not found', 'data' => null], 404);
        }

        if ($role !== 'VP' && $applicant->council != $userCouncil && $applicant->council !== null) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized', 'data' => null], 403);
        }

        $allowed = [];
        if ($role === 'Instructor') {
            $allowed = ['rating', 'notes', 'interview_time', 'interview_questions', 'interview_notes'];
        } elseif ($role === 'Head' || $role === 'VP') {
            $allowed = ['name','email','phone','college','level','rating','notes','council','interview_time','interview_questions','interview_notes','ushered_by','event_type','interviewed_by'];
        } elseif ($role === 'OR') {
            $allowed = ['name','phone','interview_time','ushered_by','event_type','interviewed_by'];
        }

        $input = $request->all();
        $fields = [];
        $ratingChanged = false;

        foreach ($allowed as $f) {
            if (array_key_exists($f, $input)) {
                if ($f === 'rating' && $input[$f] != $applicant->rating) {
                    $ratingChanged = true;
                }
                if ($f === 'interview_time' && empty($input[$f])) {
                    $fields[$f] = null;
                } elseif (($f === 'interview_questions' || $f === 'interview_notes') && is_array($input[$f])) {
                    $fields[$f] = json_encode($input[$f], JSON_UNESCAPED_UNICODE);
                } else {
                    $fields[$f] = $input[$f];
                }
            }
        }

        if ($ratingChanged) {
            $fields['interviewed_by'] = $username;
        }

        if (empty($fields)) {
            return response()->json(['status' => 'error', 'message' => 'No valid fields provided', 'data' => null], 400);
        }

        $applicant->fill($fields);
        $applicant->save();

        Cache::flush();

        return response()->json(['status' => 'success', 'message' => 'Updated successfully', 'data' => null]);
    }
}
