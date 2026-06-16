<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_title' => fake()->sentence(),
            'approved_budget' => fake()->numberBetween(10000, 1000000),
            'bidding_date' => fake()->dateTimeBetween('now', '+2 months'),
            'status' => fake()->randomElement(['awarded', 'failed']),
            'created_at' => fake()->dateTimeBetween('now', '+7 days'),
        ];
    }
}
