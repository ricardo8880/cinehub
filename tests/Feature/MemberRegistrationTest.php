<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MemberRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_logs_in_without_sending_verification_email(): void
    {
        Event::fake([Registered::class]);
        Notification::fake();
        Mail::fake();

        $response = $this->post(route('member.register.store', ['locale' => 'en-us']), [
            'name' => 'Test Member',
            'email' => 'member@example.test',
            'password' => 'test-password-123',
            'password_confirmation' => 'test-password-123',
        ]);

        $user = User::query()->where('email', 'member@example.test')->firstOrFail();
        $response->assertRedirect(route('member.for-you', ['locale' => 'en-us']));
        $this->assertAuthenticatedAs($user);
        $this->assertFalse($user->is_admin);
        $this->assertNull($user->email_verified_at);
        Event::assertNotDispatched(Registered::class);
        Notification::assertNothingSent();
        Mail::assertNothingSent();
    }

    public function test_members_only_need_to_be_logged_in_to_access_personalized_pages(): void
    {
        $user = User::factory()->create(['email_verified_at' => null]);

        $this->actingAs($user)->get(route('member.for-you', ['locale' => 'en-us']))->assertOk();
    }
}
