<?php

namespace App\Http\Controllers;

use App\Models\BookLog;
use Inertia\Inertia;

class RecommendationController extends Controller
{
    public function index()
    {
        $followingIds = auth()->user()->following()->pluck('users.id');
        $alreadyLoggedIds = BookLog::where('user_id', auth()->id())->pluck('book_id');

        $logs = BookLog::whereIn('user_id', $followingIds)
            ->whereNotIn('book_id', $alreadyLoggedIds)
            ->with('book')
            ->get();

        $recommendations = $logs->groupBy('book_id')
            ->map(fn($group) => [
                'book' => $group->first()->book,
                'frequency' => $group->count(),
            ])
            ->sortByDesc('frequency')
            ->take(10)
            ->values();

        return Inertia::render('Recommendations/Index', [
            'recommendations' => $recommendations,
        ]);
    }
}
