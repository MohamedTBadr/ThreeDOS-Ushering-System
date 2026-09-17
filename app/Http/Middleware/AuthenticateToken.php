<?php

namespace App\Http\Middleware;

use App\Models\Session;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-Token');

        if (!$token) {
            $auth = $request->header('Authorization');
            if ($auth && str_starts_with($auth, 'Bearer ')) {
                $token = substr($auth, 7);
            }
        }

        if (!$token) {
            $token = $request->query('token');
        }

        if (!$token) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized. No token provided.', 'data' => null], 401);
        }

        $session = Session::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$session) {
            return response()->json(['status' => 'error', 'message' => 'Session expired or invalid token.', 'data' => null], 401);
        }

        $user = $session->user;
        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Session expired or invalid token.', 'data' => null], 401);
        }

        // Attach user and session to request
        $request->attributes->set('auth_user', $user);
        $request->attributes->set('auth_session', $session);
        $request->attributes->set('auth_token', $token);

        return $next($request);
    }
}
