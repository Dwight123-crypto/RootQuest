<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\Hint;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChallengeTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_challenge(): void
    {
        $user = User::factory()->create();
        $challenge = Challenge::factory()->create();

        $response = $this->actingAs($user)->get(route('challenges.show', $challenge));

        $response->assertStatus(200);
        $response->assertSee($challenge->title);
    }

    public function test_user_can_submit_correct_flag(): void
    {
        $user = User::factory()->create();
        $challenge = Challenge::factory()->create(['flag' => 'FLAG{test_flag}', 'points' => 100]);

        $response = $this->actingAs($user)->post(route('challenges.submit', $challenge), [
            'flag' => 'FLAG{test_flag}',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('solved_challenges', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
        ]);

        $user->refresh();
        $this->assertEquals(100, $user->score);
    }

    public function test_user_cannot_submit_wrong_flag(): void
    {
        $user = User::factory()->create();
        $challenge = Challenge::factory()->create(['flag' => 'FLAG{correct}']);

        $response = $this->actingAs($user)->post(route('challenges.submit', $challenge), [
            'flag' => 'FLAG{wrong}',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('solved_challenges', [
            'user_id' => $user->id,
            'challenge_id' => $challenge->id,
        ]);
    }

    public function test_user_cannot_solve_same_challenge_twice(): void
    {
        $user = User::factory()->withScore(100)->create();
        $challenge = Challenge::factory()->create(['flag' => 'FLAG{test}', 'points' => 100]);

        // Solve it first time
        $user->solvedChallenges()->attach($challenge->id, ['solved_at' => now()]);

        // Try to solve again
        $response = $this->actingAs($user)->post(route('challenges.submit', $challenge), [
            'flag' => 'FLAG{test}',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        // Score should not change
        $user->refresh();
        $this->assertEquals(100, $user->score);
    }

    public function test_team_member_cannot_solve_if_teammate_already_solved(): void
    {
        $team = Team::factory()->create();
        $user1 = User::factory()->withTeam($team)->create();
        $user2 = User::factory()->withTeam($team)->create();
        $challenge = Challenge::factory()->create(['flag' => 'FLAG{team_test}', 'points' => 100]);

        // User1 solves
        $user1->solvedChallenges()->attach($challenge->id, ['solved_at' => now()]);
        $user1->increment('score', 100);

        // User2 tries to solve
        $response = $this->actingAs($user2)->post(route('challenges.submit', $challenge), [
            'flag' => 'FLAG{team_test}',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('solved_challenges', [
            'user_id' => $user2->id,
            'challenge_id' => $challenge->id,
        ]);
    }

    public function test_user_can_unlock_hint_with_sufficient_points(): void
    {
        $user = User::factory()->withScore(100)->create();
        $challenge = Challenge::factory()->create();
        $hint = Hint::factory()->forChallenge($challenge)->withCost(50)->create();

        $response = $this->actingAs($user)->post(route('challenges.unlock-hint', $challenge), [
            'hint_id' => $hint->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('user_hints', [
            'user_id' => $user->id,
            'hint_id' => $hint->id,
        ]);

        $user->refresh();
        $this->assertEquals(50, $user->score);
    }

    public function test_user_cannot_unlock_hint_without_sufficient_points(): void
    {
        $user = User::factory()->withScore(25)->create();
        $challenge = Challenge::factory()->create();
        $hint = Hint::factory()->forChallenge($challenge)->withCost(50)->create();

        $response = $this->actingAs($user)->post(route('challenges.unlock-hint', $challenge), [
            'hint_id' => $hint->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('user_hints', [
            'user_id' => $user->id,
            'hint_id' => $hint->id,
        ]);

        $user->refresh();
        $this->assertEquals(25, $user->score);
    }

    public function test_guest_cannot_access_challenge(): void
    {
        $challenge = Challenge::factory()->create();

        $response = $this->get(route('challenges.show', $challenge));

        $response->assertRedirect(route('login'));
    }
}
