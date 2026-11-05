<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\TimeBasedOneTimePassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_open_admin_dashboard(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_public_member_session_does_not_authenticate_administrator(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin, 'web')->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_non_admin_cannot_log_in_to_administrator(): void
    {
        User::factory()->create(['email' => 'member@example.com', 'password' => 'password-1234', 'is_admin' => false]);
        $this->post('/admin/login', [
            'email' => 'member@example.com', 'password' => 'password-1234',
        ])->assertSessionHasErrors('email');
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_correct_password_requires_two_factor_code_before_admin_access(): void
    {
        User::factory()->create(['email' => 'admin@example.com', 'password' => 'password-1234', 'is_admin' => true]);
        $this->post('/admin/login', [
            'email' => 'admin@example.com', 'password' => 'password-1234',
        ])->assertRedirect('/admin/two-factor');
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->get('/admin/two-factor')->assertOk();
    }

    public function test_enrollment_and_verified_code_grant_admin_access(): void
    {
        User::factory()->create(['email' => 'admin@example.com', 'password' => 'password-1234', 'is_admin' => true]);
        $this->post('/admin/login', [
            'email' => 'admin@example.com', 'password' => 'password-1234',
        ])->assertRedirect('/admin/two-factor');
        $response = $this->get('/admin/two-factor')->assertOk();
        $secret = $response->viewData('setupSecret');
        $this->assertSame(32, strlen($secret));

        $this->post('/admin/two-factor', [
            'code' => $this->codeFor($secret),
        ])->assertRedirect('/admin');
        $this->get('/admin')->assertOk();
        $this->assertDatabaseHas('users', ['email' => 'admin@example.com']);
    }

    public function test_local_admin_login_requires_only_email_and_password(): void
    {
        app()->detectEnvironment(static fn () => 'local');

        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password-1234',
            'is_admin' => true,
            'admin_two_factor_secret' => null,
        ]);

        $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'password-1234',
        ])->assertRedirect('/admin');

        $this->get('/admin')->assertOk();
    }

    public function test_local_admin_still_requires_a_valid_password(): void
    {
        app()->detectEnvironment(static fn () => 'local');

        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'password-1234',
            'is_admin' => true,
        ]);

        $this->post('/admin/login', [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_security_headers_are_present(): void
    {
        $this->get('/en-us/')->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN');
    }

    private function codeFor(string $secret): string
    {
        $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $bits = '';
        foreach (str_split($secret) as $letter) {
            $bits .= str_pad(decbin(strpos($alphabet, $letter)), 5, '0', STR_PAD_LEFT);
        }
        $key = '';
        foreach (str_split($bits, 8) as $chunk) {
            $key .= chr(bindec($chunk));
        }
        $step = intdiv(time(), 30);
        $hash = hash_hmac('sha1', pack('N2', $step >> 32, $step & 0xffffffff), $key, true);
        $offset = ord($hash[19]) & 15;
        return str_pad((string) ((unpack('N', substr($hash, $offset, 4))[1] & 0x7fffffff) % 1000000), 6, '0', STR_PAD_LEFT);
    }
}
