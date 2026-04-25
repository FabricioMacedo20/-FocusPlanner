<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlannerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_planner()
    {
        $response = $this->get('/planner');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_planner()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/planner');

        $response->assertStatus(200);
        $response->assertSee('Planejamento Diário');
        $response->assertSee('Nova tarefa');
    }

    public function test_task_crud_workflow_on_planner()
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/task/store', [
            'title' => 'Testar Planner',
            'date' => now()->format('Y-m-d'),
            'priority' => 'alta',
        ])->assertRedirect(route('planner'));

        $this->assertDatabaseHas('tasks', [
            'user_id' => $user->id,
            'title' => 'Testar Planner',
            'priority' => 'alta',
            'status' => 0,
        ]);

        $task = Task::where('user_id', $user->id)->where('title', 'Testar Planner')->first();
        $this->assertNotNull($task);

        // Completar tarefa
        $this->actingAs($user)->get(route('task.complete', $task->id))
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 1,
        ]);

        // Excluir tarefa
        $this->actingAs($user)->get(route('task.delete', $task->id))
            ->assertRedirect(route('dashboard'));

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }
}
