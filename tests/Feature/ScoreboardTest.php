<?php

namespace Tests\Feature;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ScoreboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_user_scoreboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('scoreboard.users'));

        $response->assertStatus(200);
    }

    public function test_user_can_view_team_scoreboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('scoreboard.teams'));

        $response->assertStatus(200);
    }

    public function test_user_scoreboard_excludes_admins(): void
    {
        $admin = User::factory()->admin()->withScore(1000)->create();
        $user = User::factory()->withScore(500)->create();

        $response = $this->actingAs($user)->get(route('scoreboard.users'));

        $response->assertStatus(200);
        $response->assertDontSee($admin->name);
        $response->assertSee($user->name);
    }

    public function test_users_ranked_by_score_descending(): void
    {
        $user1 = User::factory()->withScore(100)->create();
        $user2 = User::factory()->withScore(300)->create();
        $user3 = User::factory()->withScore(200)->create();

        $viewer = User::factory()->create();

        $response = $this->actingAs($viewer)->get(route('scoreboard.users'));

        $response->assertStatus(200);
        $response->assertSeeInOrder([$user2->name, $user3->name, $user1->name]);
    }

    public function test_team_score_is_sum_of_member_scores(): void
    {
        $team = Team::factory()->create();
        User::factory()->withTeam($team)->withScore(100)->create();
        User::factory()->withTeam($team)->withScore(200)->create();

        $viewer = User::factory()->create();

        $response = $this->actingAs($viewer)->get(route('scoreboard.teams'));

        $response->assertStatus(200);
        $response->assertSee('300'); // Total team score
    }

    public function test_guest_cannot_view_scoreboards(): void
    {
        $response = $this->get(route('scoreboard.users'));
        $response->assertRedirect(route('login'));

        $response = $this->get(route('scoreboard.teams'));
        $response->assertRedirect(route('login'));
    }
}
