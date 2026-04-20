<?php

namespace App\Http\Controllers;

use App\Models\BookLog;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, BookLog $bookLog): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'max:500'],
        ]);

        $bookLog->comments()->create([
            'user_id' => auth()->id(),
            'book_log_id' => $bookLog->id,
            'body' => $validated['body'],
        ]);

        return back();
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        abort_unless(auth()->id() === $comment->user_id, 403);

        $comment->delete();

        return back();
    }
}
