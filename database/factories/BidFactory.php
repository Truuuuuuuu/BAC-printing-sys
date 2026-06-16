<?php

namespace Database\Factories;

use App\Models\Bid;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bid>
 */
class BidFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id'   => Project::inRandomOrder()->first()->id,
            'company_name' => fake()->company(),
            'proprietor'   => fake()->name(),
            'bid_amount'   => fake()->numberBetween(10000, 1000000),
            'street'       => fake()->streetAddress(),
            'barangay'     => fake()->word(),
            'municipality_city' => fake()->city(),
        ];
    }
}
