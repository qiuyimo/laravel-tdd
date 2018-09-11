<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @test
     */
    public function anAuthenticatedUserCanCreateNewForumThreads()
    {
        // Given we have a signed in user
        $this->actingAs(factory('App\User')->create());  // 已登录用户

        // When we hit the endpoint to create a new thread
        $thread = factory('App\Thread')->make();
        $this->post('/threads', $thread->toArray());

        // Then, when we visit the thread
        // We should see the new thread
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /**
     * @test
     */
    public function guestsMayNotCreateThreads()
    {
        $thread = factory('App\Thread')->make();
        $this->post('/threads', $thread->toArray())->assertStatus(302);
    }
}
