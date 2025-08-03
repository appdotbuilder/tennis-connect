<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        $city = $request->get('city');
        $skillLevel = $request->get('skill_level');
        
        $events = Event::active()
            ->upcoming()
            ->when($city, function ($query, $city) {
                return $query->inCity($city);
            })
            ->when($skillLevel && $skillLevel !== 'all', function ($query, $skillLevel) {
                return $query->where('skill_level', $skillLevel);
            })
            ->orderBy('event_date')
            ->paginate(12);

        $cities = Event::active()->distinct()->pluck('city')->filter()->values();
        
        return Inertia::render('events/index', [
            'events' => $events,
            'cities' => $cities,
            'filters' => [
                'city' => $city,
                'skill_level' => $skillLevel,
            ]
        ]);
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return Inertia::render('events/show', [
            'event' => $event
        ]);
    }
}