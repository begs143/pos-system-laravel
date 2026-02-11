<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Spatie\Activitylog\Models\Activity;

class HeaderLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public $recentLogs;

    public function __construct()
    {

        // Get the last 5 activity logs
        $this->recentLogs = Activity::with('causer')
            ->latest()
            ->take(5)
            ->get();

    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.header');
    }
}
