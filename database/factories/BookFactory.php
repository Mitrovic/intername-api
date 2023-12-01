<?php

namespace Database\Factories;

use App\Models\CustomUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'author_id' => CustomUser::factory(), // Assuming you have a CustomUser model
            'cover_image' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 10, 60),
        ];
    }
}
