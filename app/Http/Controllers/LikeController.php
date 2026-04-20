<?php

namespace App\Http\Controllers;

use App\Models\BookLog;
use Illuminate\Http\RedirectResponse;

class LikeController extends Controller
{
    public function toggle(BookLog $bookLog): RedirectResponse
    {
        $existing = $bookLog->likes()->where('user_id', auth()->id())->first();

        if ($existing) {
            $existing->delete();
        } else {
            $bookLog->likes()->create([
                'user_id' => auth()->id(),
                'book_log_id' => $bookLog->id,
            ]);
        }

        return back();
    }
}
