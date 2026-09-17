<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationWebController extends Controller
{
    public function form()
    {
        return view('registration.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:registrations,email',
            'phone' => 'required|string|max:20',
            'college' => 'required|string|max:255',
            'level' => 'required|string|max:50',
            'council' => 'nullable|string|max:100',
            'event_type' => 'nullable|string|max:100',
            'ushered_by' => 'nullable|string|max:255',
        ]);

        Registration::create($validated);

        return redirect()->back()->with('success', 'Registration submitted successfully!');
    }
}
