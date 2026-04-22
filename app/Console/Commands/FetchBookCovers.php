<?php

namespace App\Console\Commands;

use App\Models\Book;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchBookCovers extends Command
{
    protected $signature = 'fetch:book-covers';

    protected $description = 'Fetch missing book cover images from Open Library';

    public function handle(): int
    {
        $books = Book::query()
            ->where(function ($query) {
                $query->whereNull('cover_image_url')
                      ->orWhere('cover_image_url', 'like', '%picsum%');
            })
            ->get();

        if ($books->isEmpty()) {
            $this->info('No books need cover images.');
            return self::SUCCESS;
        }

        $this->info("Found {$books->count()} book(s) to process.");

        foreach ($books as $book) {
            $response = Http::get('https://openlibrary.org/search.json', [
                'title' => $book->title,
                'limit' => 1,
            ]);

            if ($response->successful()) {
                $coverId = data_get($response->json(), 'docs.0.cover_i');

                if ($coverId) {
                    $book->cover_image_url = "https://covers.openlibrary.org/b/id/{$coverId}-M.jpg";
                    $book->save();
                    $this->info("[✓] \"{$book->title}\" → cover ID {$coverId}");
                } else {
                    $this->info("[–] \"{$book->title}\" — no cover found");
                }
            } else {
                $this->warn("[!] \"{$book->title}\" — request failed ({$response->status()})");
            }

            usleep(100_000); // 100 ms
        }

        $this->info('Done.');

        return self::SUCCESS;
    }
}
