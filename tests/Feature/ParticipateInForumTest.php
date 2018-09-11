<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function anAuthenticatedUserMayParticipateInForumThreads()
    {
        // Given we have an authenticated user.
        $this->be($user = factory('App\User')->create());

        // And an existing thread.
        $thread = factory('App\Thread')->create();

        // When the user adds a reply to the thread.
        $reply = factory('App\Reply')->create();
        $this->post($thread->path() . '/replies', $reply->toArray());

        // Then their reply should be visible on the page.
        $this->get($thread->path())->assertSee($reply->body);
    }

    /**
     * @test
     */
    public function unauthenticatedUserMayNoAddReplies()
    {
        // $this->expectException('Illuminate\Auth\AuthenticationException');

        $thread = factory('App\Thread')->create();

        $reply = factory('App\Reply')->create();

        $this->post($thread->path() . '/replies', $reply->toArray())->assertStatus(302);
    }
}
