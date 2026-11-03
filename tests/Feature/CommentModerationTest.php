<?php

namespace Tests\Feature;

use App\Support\CommentModeration;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentModerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_expressive_criticism_is_not_automatically_blocked(): void
    {
        $moderation = app(CommentModeration::class);
        $this->assertSame('safe', $moderation->classify('This game is fucking amazing!'));
        $this->assertSame('safe', $moderation->classify('I fucking disagree with this review.'));
    }

    public function test_targeted_abuse_and_threats_are_blocked(): void
    {
        $moderation = app(CommentModeration::class);
        $this->assertSame('blocked', $moderation->classify('You are a fucking idiot!'));
        $this->assertSame('blocked', $moderation->classify('I am going to hurt you.'));
        $this->assertSame('blocked', $moderation->classify('F.U.C.K YOU'));
    }

    public function test_ambiguous_attacks_require_review(): void
    {
        $moderation = app(CommentModeration::class);
        $this->assertSame('review', $moderation->classify('Your opinion is shit.'));
        $this->assertSame('review', $moderation->classify('Join my group at https://example.test'));
    }
}
