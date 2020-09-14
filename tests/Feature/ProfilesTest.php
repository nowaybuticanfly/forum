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


    public function test_profiles_display_all_threads_created_by_associated_user()
    {
        $this->signIn();


        $thread = factory('App\Thread')->create(['user_id' => auth()->id()]);

        $this->get('/profiles/' . auth()->user()->name)
            ->assertSee($thread->title)
            ->assertSee($thread->body);


    }

}
