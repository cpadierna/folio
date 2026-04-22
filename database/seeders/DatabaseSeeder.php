<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookLog;
use App\Models\Comment;
use App\Models\User;
use Database\Factories\BookLogFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $faker = \Faker\Factory::create();

        DB::transaction(function () use ($faker) {

            // ── 1. Users ────────────────────────────────────────────────────
            $this->command->info('Creating 500 users...');
            User::factory(500)->create();
            $userIds = User::pluck('id')->all();

            // ── 2. Books ────────────────────────────────────────────────────
            $this->command->info('Creating 100 books...');
            Book::factory(100)->create();
            $bookIds = Book::pluck('id')->all();

            // ── 3. Book logs ─────────────────────────────────────────────────
            $this->command->info('Creating book logs for each user...');
            foreach ($userIds as $userId) {
                $count   = $faker->numberBetween(3, 25);
                $sampled = $faker->randomElements($bookIds, min($count, count($bookIds)));

                foreach ($sampled as $bookId) {
                    BookLog::factory()->create([
                        'user_id' => $userId,
                        'book_id' => $bookId,
                    ]);
                }
            }

            // ── 4. Follows ───────────────────────────────────────────────────
            $this->command->info('Creating follows...');
            foreach ($userIds as $userId) {
                $count   = $faker->numberBetween(2, 20);
                $others  = array_values(array_filter($userIds, fn ($id) => $id !== $userId));
                $targets = $faker->randomElements($others, min($count, count($others)));

                $rows = array_map(fn ($followingId) => [
                    'follower_id'  => $userId,
                    'following_id' => $followingId,
                    'created_at'   => $faker->dateTimeBetween('-1 year', 'now'),
                ], $targets);

                DB::table('follows')->insertOrIgnore($rows);
            }

            // ── 5. Likes ─────────────────────────────────────────────────────
            $this->command->info('Creating likes...');
            $bookLogIds = BookLog::pluck('id')->all();

            foreach ($userIds as $userId) {
                $count   = $faker->numberBetween(0, 30);
                $sampled = $faker->randomElements($bookLogIds, min($count, count($bookLogIds)));

                $rows = array_map(fn ($bookLogId) => [
                    'user_id'     => $userId,
                    'book_log_id' => $bookLogId,
                    'created_at'  => $faker->dateTimeBetween('-1 year', 'now'),
                ], $sampled);

                if (!empty($rows)) {
                    DB::table('likes')->insertOrIgnore($rows);
                }
            }

            // ── 6. Comments ───────────────────────────────────────────────────
            $this->command->info('Creating comments on 20% of book logs...');
            $commentTargets = $faker->randomElements(
                $bookLogIds,
                (int) round(count($bookLogIds) * 0.2)
            );

            foreach ($commentTargets as $bookLogId) {
                $commentCount = $faker->numberBetween(1, 5);
                $commenters   = $faker->randomElements($userIds, min($commentCount, count($userIds)));

                foreach ($commenters as $commenterId) {
                    Comment::factory()->create([
                        'user_id'     => $commenterId,
                        'book_log_id' => $bookLogId,
                    ]);
                }
            }

            // ── 7. Demo account ──────────────────────────────────────────────
            $this->command->info('Creating demo account...');

            $demoUser = User::factory()->create([
                'name'     => 'Demo User',
                'email'    => 'demo@folio.app',
                'password' => bcrypt('password'),
            ]);

            $demoBooks = [
                ['title' => '1984',                                  'author' => 'George Orwell',           'rating' => 5.0],
                ['title' => 'The Great Gatsby',                      'author' => 'F. Scott Fitzgerald',     'rating' => 4.0],
                ['title' => 'Brave New World',                       'author' => 'Aldous Huxley',           'rating' => 4.5],
                ['title' => 'Dune',                                  'author' => 'Frank Herbert',           'rating' => 5.0],
                ['title' => 'Crime and Punishment',                  'author' => 'Fyodor Dostoevsky',       'rating' => 4.5],
                ['title' => 'The Hitchhiker\'s Guide to the Galaxy', 'author' => 'Douglas Adams',           'rating' => 4.5],
                ['title' => 'Meditations',                           'author' => 'Marcus Aurelius',         'rating' => 5.0],
                ['title' => 'The Road',                              'author' => 'Cormac McCarthy',         'rating' => 4.0],
                ['title' => 'Sapiens: A Brief History of Humankind', 'author' => 'Yuval Noah Harari',       'rating' => 4.0],
                ['title' => 'Never Let Me Go',                       'author' => 'Kazuo Ishiguro',          'rating' => 4.5],
            ];

            foreach ($demoBooks as $entry) {
                $book = Book::firstOrCreate(
                    ['title' => $entry['title']],
                    array_merge(
                        Book::factory()->make(['title' => $entry['title']])->toArray(),
                        ['author' => $entry['author']]
                    )
                );

                BookLog::firstOrCreate(
                    ['user_id' => $demoUser->id, 'book_id' => $book->id],
                    [
                        'status' => 'read',
                        'rating' => $entry['rating'],
                        'notes'  => null,
                        'review' => null,
                    ]
                );
            }

            $this->command->info('Seeding complete.');
        });
    }
}

