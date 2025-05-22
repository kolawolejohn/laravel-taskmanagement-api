<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => (string) Str::uuid(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph,
            'status' => $this->faker->randomElement(['pending', 'completed']),
            'scheduled_for' => $this->faker->optional()->dateTimeBetween('now', '+1 week'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
