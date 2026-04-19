<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class FollowController extends Controller
{
    public function toggle(User $user): RedirectResponse
    {
        $authUser = auth()->user();

        if ($authUser->following()->where('following_id', $user->id)->exists()) {
            $authUser->following()->detach($user->id);
            $message = 'You have unfollowed ' . $user->name . '.';
        } else {
            $authUser->following()->attach($user->id);
            $message = 'You are now following ' . $user->name . '.';
        }

        return back()->with('success', $message);
    }
}
