<?php

namespace App\Http\Controllers;

use App\Models\BookLog;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $recentLogs = BookLog::with('book')
            ->latest()
            ->take(16)
            ->get();

        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'recentLogs' => $recentLogs,
        ]);
    }
}
