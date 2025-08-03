<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\Event;
use App\Models\TennisMatch;
use Inertia\Inertia;

class HomeController extends Controller
{
    /**
     * Display the home page with tennis platform overview.
     */
    public function index()
    {
        $recentProfiles = Profile::with('user')
            ->lookingForPartners()
            ->latest()
            ->limit(6)
            ->get();

        $upcomingEvents = Event::active()
            ->upcoming()
            ->orderBy('event_date')
            ->limit(4)
            ->get();

        $upcomingMatches = TennisMatch::with(['organizer', 'participants'])
            ->open()
            ->upcoming()
            ->orderBy('match_date')
            ->limit(4)
            ->get();

        $stats = [
            'profiles_count' => Profile::lookingForPartners()->count(),
            'events_count' => Event::active()->upcoming()->count(),
            'matches_count' => TennisMatch::open()->upcoming()->count(),
        ];

        return Inertia::render('welcome', [
            'recentProfiles' => $recentProfiles,
            'upcomingEvents' => $upcomingEvents,
            'upcomingMatches' => $upcomingMatches,
            'stats' => $stats,
        ]);
    }
}