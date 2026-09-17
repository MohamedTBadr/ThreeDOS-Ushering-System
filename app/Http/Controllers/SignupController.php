<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:VP,Head,Instructor,OR,President',
            'council' => 'nullable|string|max:100',
        ]);

        $username = $request->input('username');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $role = $request->input('role');
        $council = $request->input('council');

        $rolesWithoutCouncil = ['VP', 'President', 'OR'];
        $rolesWithCouncil = ['Head', 'Instructor'];

        if (in_array($role, $rolesWithoutCouncil, true)) {
            $council = null;
        } elseif (in_array($role, $rolesWithCouncil, true)) {
            if (empty($council)) {
                return response()->json(['status' => 'error', 'message' => "Council is required for role $role", 'data' => null], 400);
            }
        }

        $exists = User::where('username', $username)->orWhere('email', $email)->exists();
        if ($exists) {
            return response()->json(['status' => 'error', 'message' => 'Username or Email already registered', 'data' => null], 409);
        }

        $user = User::create([
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'council' => $council,
        ]);

        return response()->json(['status' => 'success', 'message' => 'User registered successfully', 'data' => ['id' => $user->id]], 201);
    }
}
