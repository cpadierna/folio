<?php

namespace Database\Factories;

use App\Models\BookLog;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comment>
 */
class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'book_log_id' => BookLog::factory(),
            'body'        => fake()->sentences(fake()->numberBetween(1, 3), true),
        ];
    }
}
