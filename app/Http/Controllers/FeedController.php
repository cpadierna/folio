<?php

namespace App\Http\Controllers;

use App\Models\BookLog;
use Inertia\Inertia;
use Inertia\Response;

class FeedController extends Controller
{
    public function index(): Response
    {
        $followingIds = auth()->user()->following()->pluck('users.id');

        $logs = BookLog::whereIn('user_id', $followingIds)
            ->with(['user:id,name', 'book:id,google_books_id,title,author,cover_image_url'])
            ->latest()
            ->paginate(20);

        return Inertia::render('Feed/Index', [
            'logs' => $logs,
        ]);
    }
}
