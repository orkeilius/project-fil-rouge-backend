<?php

namespace Database\Factories;

use App\Models\Auction;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => "https://picsum.photos/id/".fake()->numberBetween(1,1080)."/600",
            'description' => fake()->text(150),
            'created_at' => now(),
            'auction_id' => fake()->numberBetween(1, Auction::count()),
        ];
    }
}
