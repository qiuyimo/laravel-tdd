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
    public function unauthenticated_user_may_no_add_replies()
    {
        $this->withExceptionHandling()
            ->post('threads/some-channel/1/replies',[])
            ->assertRedirect('/login');
    }

    /** @test */
    public function a_reply_reqiures_a_body()
    {
        $this->withExceptionHandling()->signIn();

        $thread = create('App\Thread');
        $reply = make('App\Reply',['body' => null]);

        $this->post($thread->path() . '/replies',$reply->toArray())
            ->assertSessionHasErrors('body');
    }
}
