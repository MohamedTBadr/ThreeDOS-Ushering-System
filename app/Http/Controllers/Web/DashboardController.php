<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Server-side data for initial render (MVC) — view also hydrates via JS/API
        $stats = [
            'total' => Registration::count(),
            'accepted' => Registration::where('rating', 'Acceptance')->count(),
            'backup' => Registration::where('rating', 'B')->count(),
            'rejected' => Registration::where('rating', 'Rejection')->count(),
            'pending' => Registration::where(function ($q) { $q->whereNull('rating')->orWhere('rating', 'Pending'); })->count(),
        ];

        $applicants = Registration::orderBy('id', 'desc')->limit(20)->get();

        return view('dashboard.index', compact('stats', 'applicants'));
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
        $stats = Registration::selectRaw('ushered_by, COUNT(*) as count')->whereNotNull('ushered_by')->groupBy('ushered_by')->get();
        return view('dashboard.usher-stats', compact('stats'));
    }
}
