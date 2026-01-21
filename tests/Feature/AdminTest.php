<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Challenge;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
    }

    public function test_non_admin_cannot_access_admin_panel(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_category(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Web Security',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Web Security',
        ]);
    }

    public function test_admin_can_create_challenge(): void
    {
        $admin = User::factory()->admin()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.challenges.store'), [
            'title' => 'Test Challenge',
            'description' => 'This is a test challenge description.',
            'flag' => 'FLAG{test_flag}',
            'points' => 100,
            'category_id' => $category->id,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('challenges', [
            'title' => 'Test Challenge',
            'flag' => 'FLAG{test_flag}',
            'points' => 100,
        ]);
    }

    public function test_admin_can_create_team(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->post(route('admin.teams.store'), [
            'name' => 'Alpha Team',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('teams', [
            'name' => 'Alpha Team',
        ]);
    }

    public function test_admin_can_update_user_role(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin.users.update-role'), [
            'user_id' => $user->id,
            'is_admin' => true,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertTrue($user->is_admin);
    }

    public function test_admin_can_delete_challenge(): void
    {
        $admin = User::factory()->admin()->create();
        $challenge = Challenge::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.challenges.destroy', $challenge));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('challenges', [
            'id' => $challenge->id,
        ]);
    }
}
