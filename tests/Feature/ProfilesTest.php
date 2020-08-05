<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;

class ProfilesTest extends TestCase
{
    use RefreshDatabase;


    public function test_a_user_has_a_profile()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->get('/profiles/' . $user->name)
            ->assertSee($user->name);
    }


    public function test_profiles_display_all_threads_created_by_assosiated_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $thread = factory('App\Thread')->create(['user_id' => $user->id]);

        $this->get('/profiles/' . $user->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);


    }

}
