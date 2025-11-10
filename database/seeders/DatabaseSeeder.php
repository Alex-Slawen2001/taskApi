<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $user1 = User::factory()->create([
            'name' => 'Alice Manager',
            'email' => 'alice@example.com',
            'password' => bcrypt('password'),
        ]);

        $user2 = User::factory()->create([
            'name' => 'Bob Executor',
            'email' => 'bob@example.com',
            'password' => bcrypt('password'),
        ]);

        $token1 = $user1->createToken('auth-token')->plainTextToken;
        $token2 = $user2->createToken('auth-token')->plainTextToken;

        $this->command->info("Token for Alice Manager: $token1");
        $this->command->info("Token for Bob Executor: $token2");

        // Projects
        $projectA = Project::create(['name' => 'Internal Tools']);
        $projectB = Project::create(['name' => 'Client Portal']);

        // Tasks
        Task::create([
            'project_id' => $projectA->id,
            'title' => 'Согласовать ТЗ',
            'description' => 'Подготовить и согласовать ТЗ с заказчиком',
            'status' => 'planned',
            'due_date' => now()->addDays(7)->format('Y-m-d'),
            'assignee_id' => $user2->id,
        ]);

        Task::create([
            'project_id' => $projectA->id,
            'title' => 'Настроить CI/CD',
            'description' => 'Добавить проверки и деплой',
            'status' => 'in_progress',
            'due_date' => now()->addDays(3)->format('Y-m-d'),
            'assignee_id' => $user1->id,
        ]);

        Task::create([
            'project_id' => $projectB->id,
            'title' => 'Верстка главной',
            'description' => 'Сверстать главную страницу по макету',
            'status' => 'done',
            'due_date' => now()->subDay()->format('Y-m-d'),
            'assignee_id' => $user2->id,
        ]);
    }
}
