<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with('causer')->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {

                // Module
                $q->where('log_name', 'like', "%{$search}%")

                  // Action
                    ->orWhere('description', 'like', "%{$search}%")

                  // Details (JSON)
                    ->orWhere('properties', 'like', "%{$search}%")

                  // User (causer)
                    ->orWhereHas('causer', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $activities = $query->paginate(25)->withQueryString();

        return view('pages.logs.index', compact('activities'));
    }
}
