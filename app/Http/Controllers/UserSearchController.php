<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserSearchController extends Controller
{
    public function index(Request $request): Response
    {
        $q = $request->query('q', '');

        if ($q === '' || $q === null) {
            return Inertia::render('Users/Search', ['users' => [], 'query' => '']);
        }

        $users = User::where('name', 'like', '%' . $q . '%')
            ->where('id', '!=', auth()->id())
            ->withCount(['followers', 'following'])
            ->take(20)
            ->get()
            ->map(function ($user) {
                $user->isFollowing = auth()->user()->following()->where('following_id', $user->id)->exists();
                return $user;
            });

        return Inertia::render('Users/Search', ['users' => $users, 'query' => $q]);
    }
}
