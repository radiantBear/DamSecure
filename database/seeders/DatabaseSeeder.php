<?php

namespace Database\Seeders;

use App\Models;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database
     */
    public function run(): void
    {
        $users = Models\User::factory(10)->create();
        $projects = Models\Project::factory(5)->create();

        foreach ($projects as $project) {
            $assignedUsers = $users->random(rand(2, 5));

            foreach ($assignedUsers as $user) {
                Models\ProjectUser::factory()->create([
                    'project_id' => $project->id,
                    'user_id' => $user->id
                ]);
            }

            Models\Data::factory(rand(1, 4))->create(['project_id' => $project->id]);
        }
    }
}
