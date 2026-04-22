<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\BookLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BookLog>
 */
class BookLogFactory extends Factory
{
    protected $model = BookLog::class;

    public function definition(): array
    {
        return [
            'user_id'  => User::factory(),
            'book_id'  => Book::factory(),
            'status'   => $this->faker->randomElement(['want_to_read', 'reading', 'read']),
            'rating'   => $this->faker->randomElement([null, 1.0, 1.5, 2.0, 2.5, 3.0, 3.5, 4.0, 4.5, 5.0]),
            'notes'    => $this->faker->optional(0.6)->sentences(2, true),
            'review'   => $this->faker->optional(0.4)->paragraph(),
        ];
    }
}
