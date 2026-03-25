<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class LibraryController extends Controller
{
    public function index()
    {
        $logs = auth()->user()
            ->bookLogs()
            ->with('book')
            ->latest()
            ->get()
            ->groupBy('status');

        return Inertia::render('Library/Index', [
            'logs' => $logs,
        ]);
    }
}
