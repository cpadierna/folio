<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookLog;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BookLogController extends Controller
{
    public function show(BookLog $bookLog): Response
    {
        $bookLog->load(['book', 'user', 'comments.user', 'likes']);

        return Inertia::render('BookLog/Show', [
            'bookLog'      => $bookLog,
            'likesCount'   => $bookLog->likes()->count(),
            'userHasLiked' => $bookLog->likes()->where('user_id', auth()->id())->exists(),
            'comments'     => $bookLog->comments()->with('user')->latest()->get(),
        ]);
    }

    public function store(Request $request, Book $book)
    {
        $validated = $request->validate([
            'status' => 'required|in:want_to_read,reading,read',
            'rating' => 'nullable|numeric|min:1|max:5',
            'notes' => 'nullable|string|max:1000',
        ]);

        BookLog::updateOrCreate(
            ['user_id' => auth()->id(), 'book_id' => $book->id],
            $validated
        );

        return redirect()->back()->with('success', 'Book logged successfully!');
    }

    public function destroy(Book $book)
    {
        BookLog::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->delete();

        return redirect()->back()->with('success', 'Log entry removed.');
    }
}
