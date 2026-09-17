<?php

namespace App\Http\Controllers;

use App\Models\Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid credentials', 'data' => null], 401);
        }

        $token = bin2hex(random_bytes(32));
        $expiresAt = now()->addHours(24);

        Session::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);

        $data = $user->toArray();
        unset($data['password']);
        $data['token'] = $token;
        $data['expires_at'] = $expiresAt->toDateTimeString();

        return response()->json(['status' => 'success', 'message' => 'Login successful', 'data' => $data]);
    }

    public function verify(Request $request)
    {
        // Token can be in header or query
        $token = $request->header('X-Token') ?? $request->query('token');

        if (!$token) {
            $auth = $request->header('Authorization');
            if ($auth && str_starts_with($auth, 'Bearer ')) {
                $token = substr($auth, 7);
            }
        }

        if (!$token) {
            return response()->json(['status' => 'error', 'message' => 'No token provided', 'data' => null], 401);
        }

        $session = Session::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$session) {
            return response()->json(['status' => 'error', 'message' => 'Invalid or expired token', 'data' => null], 401);
        }

        $user = $session->user;
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Invalid or expired token', 'data' => null], 401);
        }

        $data = $user->toArray();
        unset($data['password']);

        return response()->json(['status' => 'success', 'message' => 'Token valid', 'data' => $data]);
    }

    public function logout(Request $request)
    {
        $token = $request->header('X-Token');
        if (!$token) {
            $auth = $request->header('Authorization');
            if ($auth && str_starts_with($auth, 'Bearer ')) {
                $token = substr($auth, 7);
            }
        }

        if (!$token) {
            return response()->json(['status' => 'error', 'message' => 'No token provided', 'data' => null], 401);
        }

        $deleted = Session::where('token', $token)->delete();

        if ($deleted) {
            return response()->json(['status' => 'success', 'message' => 'Logout successful', 'data' => null]);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid token or already logged out', 'data' => null], 400);
    }
}
