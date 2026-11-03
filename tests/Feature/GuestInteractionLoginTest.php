<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestInteractionLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_remembers_the_article_within_the_same_region(): void
    {
        $destination = route('home', ['locale' => 'en-us']).'#comments';

        $this->get(route('member.login', ['locale' => 'en-us', 'return_to' => $destination]))
            ->assertOk()->assertSessionHas('url.intended', $destination);
    }

    public function test_login_does_not_accept_external_or_another_regions_destination(): void
    {
        $this->get(route('member.login', ['locale' => 'en-us', 'return_to' => 'https://evil.example/']))
            ->assertSessionMissing('url.intended');

        $this->get(route('member.login', ['locale' => 'en-us', 'return_to' => route('home', ['locale' => 'en-gb'])]))
            ->assertSessionMissing('url.intended');
    }

    public function test_registration_returns_to_the_content_the_guest_was_reading(): void
    {
        $destination = route('home', ['locale' => 'en-us']).'#comments';

        $this->get(route('member.login', ['locale' => 'en-us', 'return_to' => $destination]))
            ->assertSessionHas('url.intended', $destination);

        $this->post(route('member.register.store', ['locale' => 'en-us']), [
            'name' => 'Visitor',
            'email' => 'visitor@example.test',
            'password' => 'long-test-password',
            'password_confirmation' => 'long-test-password',
        ])->assertRedirect($destination);

        $this->assertAuthenticated();
    }
}
