<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Session;
use App\Models\User;
use App\Support\UsheringLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthWebController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required']);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            UsheringLog::warning('Failed login attempt', [
                'context' => ['email' => $request->email],
            ]);
            return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
        }

        $token = bin2hex(random_bytes(32));
        Session::create(['user_id' => $user->id, 'token' => $token, 'expires_at' => now()->addDay()]);

        session(['usher_token' => $token, 'user' => $user]);
        UsheringLog::info('User logged in', [
            'user' => $user->username,
            'context' => ['role' => $user->role, 'council' => $user->council],
        ]);
        return redirect()->route('dashboard')->with('success', 'Welcome '.$user->username);
    }

    public function registerForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:VP,Head,Instructor,OR,President',
            'council' => 'nullable|string',
        ]);

        $council = $request->council;
        if (in_array($request->role, ['VP','President','OR'])) $council = null;
        if (in_array($request->role, ['Head','Instructor']) && empty($council)) {
            return back()->withErrors(['council' => "Council required for {$request->role}"])->withInput();
        }

        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'council' => $council,
        ]);

        UsheringLog::info('New system account created', [
            'context' => ['username' => $request->username, 'role' => $request->role, 'council' => $council],
        ]);

        return redirect()->route('login')->with('success', 'Account created. Please login.');
    }

    public function logout(Request $request)
    {
        $user = session('user');
        UsheringLog::info('User logged out', [
            'user' => is_object($user) ? ($user->username ?? null) : ($user['username'] ?? null),
        ]);

        $token = session('usher_token') ?? $request->header('X-Token');
        if ($token) Session::where('token', $token)->delete();
        $request->session()->flush();
        return redirect()->route('login');
    }
}
