<?php

namespace Tests\Feature;

use App\Models\Session;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsheringLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_writes_structured_log_and_logs_page_renders(): void
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

        // Exercising an action that should write to the dedicated channel.
        $this->post('/login', [
            'email' => 'tester@example.com',
            'password' => 'secret123',
        ])->assertRedirect('/dashboard');

        $files = glob(storage_path('logs/ushering-*.log'));
        $this->assertNotEmpty($files, 'ushering log file was not created');

        $content = file_get_contents(end($files));
        $this->assertStringContainsString('"channel":"ushering"', $content);
        $this->assertStringContainsString('"time":', $content);
        $this->assertStringContainsString('"ip":', $content);
        $this->assertStringContainsString('"level":"', $content);
        $this->assertStringContainsString('"user":"tester"', $content);
        $this->assertStringContainsString('User logged in', $content);

        // The System Logs page should surface the structured file + entries.
        $this->withSession(['usher_token' => $token])
            ->get('/logs')
            ->assertOk()
            ->assertSee('ushering-', false)
            ->assertSee('"channel":"ushering"', false);
    }
}
