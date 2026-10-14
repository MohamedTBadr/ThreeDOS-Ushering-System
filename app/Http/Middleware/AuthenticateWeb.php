<?php

namespace App\Http\Middleware;

use App\Models\Session;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWeb
{
    /**
     * Protect web (Blade MVC) pages. Guests are redirected to the login page.
     * The authenticated user is refreshed onto the session for each request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->session()->get('usher_token');

        if ($token) {
            $session = Session::where('token', $token)
                ->where('expires_at', '>', now())
                ->first();

            if ($session && $session->user) {
                $request->session()->put('user', $session->user);
                return $next($request);
            }
        }

        // Invalid / expired session: forget stale auth and send the guest to login.
        $request->session()->forget(['usher_token', 'user']);

        return redirect()->guest(route('login'));
    }
}
