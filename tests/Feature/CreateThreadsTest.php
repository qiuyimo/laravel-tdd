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
    public function an_authenticated_user_can_create_new_forum_threads()
    {
        // Given we have a signed in user
        $this->signIn();  // 已登录用户

        // When we hit the endpoint to create a new thread
        $thread = factory('App\Thread')->make();
        $response = $this->post('/threads', $thread->toArray());

        // Then, when we visit the thread
        // We should see the new thread
        $this->get($response->headers->get('Location'))
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /**
     * @test
     */
    public function guestsMayNotCreateThreads()
    {
        $thread = factory('App\Thread')->make();
        $this->withExceptionHandling()->post('/threads', $thread->toArray())->assertStatus(302);
    }

    /**
     * @test
     */
    public function guestsMayNotSeeTheCreateThreadPage()
    {
        $this->withExceptionHandling()->get('/threads/create')->assertRedirect('/login');
    }

    /** @test */
    public function a_thread_requires_a_title_body_channel_id()
    {
        $this->withExceptionHandling()->signIn();

        $thread = make('App\Thread', ['title' => null]);
        $this->post('/threads', $thread->toArray())->assertSessionHasErrors('title');

        $thread = make('App\Thread', ['body' => null]);
        $this->post('/threads', $thread->toArray())->assertSessionHasErrors('body');

        $thread = make('App\Thread', ['channel_id' => null]);
        $this->post('/threads', $thread->toArray())->assertSessionHasErrors('channel_id');

        $thread = make('App\Thread', ['channel_id' => 999]);
        $this->post('/threads', $thread->toArray())->assertSessionHasErrors('channel_id');
    }

    /** @test */
    public function authorized_users_can_delete_threads()
    {
        $this->signIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);

        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }

    /** @test */
    public function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())->assertRedirect('/login');

        $this->signIn();
        $this->delete($thread->path())->assertStatus(403);
    }
}
