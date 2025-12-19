<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Author;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
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
            "title" => $this->faker->sentence(3),
            "isbn" => $this->faker->isbn13(),
            "description" => $this->faker->paragraph(),
            "edition" => $this->faker->numberBetween(1, 10) . 'th',
            "author_id" => Author::inRandomOrder()->first()?->id ?? Author::factory(),
            "total_copies" => $this->faker->numberBetween(1, 50),
            "available_copies" => $this->faker->numberBetween(0, 50),
            "price" => $this->faker->randomFloat(2, 50, 200),
            "status" => $this->faker->randomElement(['active', 'inactive'])
        ];
    }
}
