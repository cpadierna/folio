<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleBooksService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://www.googleapis.com/books/v1';

    public function __construct()
    {
        $this->apiKey = config('services.google_books.key');
    }

    public function search(string $query): array
    {
        $response = Http::get("{$this->baseUrl}/volumes", [
            'q' => $query,
            'maxResults' => 12,
            'key' => $this->apiKey,
        ]);

        if ($response->failed()) {
            return [];
        }

        return collect($response->json('items', []))
            ->map(fn($item) => $this->formatBook($item))
            ->toArray();
    }

    public function find(string $googleBooksId): ?array
    {
        $response = Http::get("{$this->baseUrl}/volumes/{$googleBooksId}", [
            'key' => $this->apiKey,
        ]);

        if ($response->failed()) {
            return null;
        }

        return $this->formatBook($response->json());
    }

    protected function formatBook(array $item): array
    {
        $info = $item['volumeInfo'] ?? [];

        return [
            'google_books_id' => $item['id'],
            'title' => $info['title'] ?? 'Unknown Title',
            'author' => isset($info['authors']) ? implode(', ', $info['authors']) : null,
            'cover_image_url' => $info['imageLinks']['thumbnail'] ?? null,
            'description' => $info['description'] ?? null,
            'published_date' => $info['publishedDate'] ?? null,
        ];
    }
}
