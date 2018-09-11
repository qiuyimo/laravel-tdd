<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }

    /**
     * @test
     */
    public function aUserCanViewAllThreads()
    {
        $this->get('/threads')->assertSee($this->thread->title);
    }

    /**
     * @test
     */
    public function aUserCanReadASingleThread()
    {
        $this->get('/threads/' . $this->thread->id)->assertSee($this->thread->title);
    }

    /**
     * @test
     */
    public function aUserCanReadRepliesThatAreAssociatedWithAThread()
    {
        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);

        $this->get('/threads/' . $this->thread->id)->assertSee($reply->body);
    }
}
