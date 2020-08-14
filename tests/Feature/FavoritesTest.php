<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;



class FavoritesTest extends TestCase
{
    use RefreshDatabase;


    public function test_guest_can_not_favorite_anything()
    {

        $this->post('/replies/1/favorites')
            ->assertRedirect('/login');


    }

    public function test_an_authenticated_user_can_favorite_a_reply()
    {

        $this->signIn();

        $reply = factory('App\Reply')->create();

        //If i post to favorites endpoint


        $this->post('/replies/' . $reply->id . '/favorites');


        //It should be recoreded in the database

        $this->assertCount(1, $reply->favorites);
    }


    public function test_an_authenticated_user_can_favorite_a_reply_only_once()
    {

        $this->withoutExceptionHandling();

        $this->signIn();

        $reply = factory('App\Reply')->create();

        try {

            $this->post('/replies/' . $reply->id . '/favorites');
            $this->post('/replies/' . $reply->id . '/favorites');
        }catch (\Exception $e){
            $this->fail('Did not expect to insert same record twice');
        }

        //It should be recoreded in the database

        $this->assertCount(1, $reply->favorites);
    }

    public function test_an_autheticated_user_can_unfavorite_a_reply()
    {
        $this->withoutExceptionHandling();
        $this->signIn();


        $reply = factory('App\Reply')->create();

        $this->post('/replies/' . $reply->id . '/favorites');


        $this->delete('/replies/' . $reply->id . '/favorites');


        $this->assertEquals(0, $reply->fresh()->favorites->count());



    }

}

