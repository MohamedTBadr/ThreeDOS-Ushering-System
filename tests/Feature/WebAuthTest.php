<?php

namespace Tests\Feature;

use App\Models\Session;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class WebAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/calendar')->assertRedirect('/login');
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/statistics')->assertRedirect('/login');
        $this->get('/logs')->assertRedirect('/login');
    }

    public function test_public_pages_are_open(): void
    {
        $this->get('/')->assertOk();
        $this->get('/register')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/signup')->assertOk();
    }

    public function test_authenticated_user_can_view_calendar(): void
    {
        $user = User::create([
            'username' => 'tester',
            'email' => 'tester@example.com',
            'password' => Hash::make('secret123'),
            'role' => 'VP',
            'council' => null,
        ]);

        $token = bin2hex(random_bytes(32));
        Session::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addDay(),
        ]);

        $this->withSession(['usher_token' => $token])
            ->get('/calendar')
            ->assertOk();
    }
}
