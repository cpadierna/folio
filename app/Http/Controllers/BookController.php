<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Services\GoogleBooksService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    public function __construct(protected GoogleBooksService $googleBooks) {}

    public function search(Request $request)
    {
        $results = [];

        if ($request->filled('q')) {
            $results = $this->googleBooks->search($request->q);
        }

        return Inertia::render('Books/Search', [
            'results' => $results,
            'query' => $request->q ?? '',
        ]);
    }

    public function show(string $googleBooksId)
    {
        $book = Book::firstOrCreate(
            ['google_books_id' => $googleBooksId],
            $this->googleBooks->find($googleBooksId) ?? []
        );

        return Inertia::render('Books/Show', [
            'book' => $book,
        ]);
    }
}
