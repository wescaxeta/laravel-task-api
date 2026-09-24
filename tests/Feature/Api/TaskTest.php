<?php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_list_own_tasks(): void
    {
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);
        Task::factory()->create();

        $response = $this->actingAs($this->user)->getJson('/api/tasks');

        $response->assertStatus(200)->assertJsonCount(3, 'data');
    }

    public function test_user_can_create_task(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/tasks', [
            'title'  => 'Estudar Laravel',
            'status' => 'pending',
        ]);

        $response->assertStatus(201)->assertJsonFragment(['title' => 'Estudar Laravel']);
        $this->assertDatabaseHas('tasks', ['title' => 'Estudar Laravel', 'user_id' => $this->user->id]);
    }

    public function test_create_task_requires_title(): void
    {
        $response = $this->actingAs($this->user)->postJson('/api/tasks', []);

        $response->assertStatus(422)->assertJsonValidationErrors(['title']);
    }

    public function test_user_can_view_own_task(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)->assertJsonFragment(['title' => $task->title]);
    }

    public function test_user_cannot_view_other_users_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->user)->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(403);
    }

    public function test_user_can_update_own_task(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->putJson("/api/tasks/{$task->id}", [
            'status' => 'done',
        ]);

        $response->assertStatus(200)->assertJsonFragment(['status' => 'done']);
    }

    public function test_user_cannot_update_other_users_task(): void
    {
        $task = Task::factory()->create();

        $response = $this->actingAs($this->user)->putJson("/api/tasks/{$task->id}", [
            'status' => 'done',
        ]);

        $response->assertStatus(403);
    }

    public function test_user_can_delete_own_task(): void
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_unauthenticated_user_cannot_access_tasks(): void
    {
        $response = $this->getJson('/api/tasks');

        $response->assertStatus(401);
    }
}
