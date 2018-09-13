<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class FavoritesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function an_authenticated_user_can_favorite_any_reply()
    {
        $this->signIn();

        $reply = create('App\Reply');

        // If I post a "favorite" endpoint
        try{
            $this->post('replies/' . $reply->id . '/favorites');
            $this->post('replies/' . $reply->id . '/favorites');
        }catch (\Exception $e){
            $this->fail('Did not expect to insert the same record set twice.');
        }


        // It Should be record in the database
        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    public function guests_can_not_favorite_anything()
    {
        $this->withExceptionHandling()->post('/replies/1/favorites')->assertRedirect('/login');
    }
}
