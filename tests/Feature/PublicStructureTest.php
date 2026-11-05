<?php

namespace Tests\Feature;

use App\Models\Suggestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicStructureTest extends TestCase
{
    use RefreshDatabase;

    public function test_legal_and_contact_pages_are_available(): void
    {
        $this->get('/en/legal/privacy')->assertOk();
        $this->get('/en/legal/terms')->assertOk();
        $this->get('/en/legal/cookies')->assertOk();
        $this->get('/en/contact')->assertOk();
        $this->get('/en/suggest')->assertOk();
    }

    public function test_visitor_can_submit_suggestion(): void
    {
        $this->post('/en/suggest', [
            'subject' => 'A future review',
            'message' => 'Please review this title when possible.',
            'email' => 'reader@example.com',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('suggestions', ['subject' => 'A future review', 'status' => 'pending']);
    }
}
