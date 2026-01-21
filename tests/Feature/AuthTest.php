<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'testuser',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('users', [
            'name' => 'testuser',
            'email' => 'test@example.com',
        ]);
    }

    public function test_first_user_becomes_admin(): void
    {
        $response = $this->post('/register', [
            'name' => 'firstuser',
            'email' => 'first@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'first@example.com')->first();
        $this->assertTrue($user->is_admin);
    }

    public function test_second_user_is_not_admin(): void
    {
        // Create first user
        User::factory()->create();

        // Register second user
        $response = $this->post('/register', [
            'name' => 'seconduser',
            'email' => 'second@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'second@example.com')->first();
        $this->assertFalse($user->is_admin);
    }

    public function test_user_can_register_with_team(): void
    {
        $team = Team::factory()->create();

        $response = $this->post('/register', [
            'name' => 'teamuser',
            'email' => 'team@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'team_id' => $team->id,
        ]);

        $user = User::where('email', 'team@example.com')->first();
        $this->assertEquals($team->id, $user->team_id);
    }

    public function test_user_cannot_join_full_team(): void
    {
        $team = Team::factory()->create();

        // Fill the team with 4 users
        User::factory()->count(4)->withTeam($team)->create();

        $response = $this->post('/register', [
            'name' => 'overflow',
            'email' => 'overflow@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'team_id' => $team->id,
        ]);

        $response->assertSessionHasErrors('team_id');
    }
}
