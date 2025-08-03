<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTennisMatchRequest;
use App\Http\Requests\UpdateTennisMatchRequest;
use App\Models\TennisMatch;
use App\Models\MatchParticipant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TennisMatchController extends Controller
{
    /**
     * Display a listing of matches.
     */
    public function index(Request $request)
    {
        $city = $request->get('city');
        $skillLevel = $request->get('skill_level');
        $matchType = $request->get('match_type');
        
        $matches = TennisMatch::with(['organizer', 'participants.user'])
            ->open()
            ->upcoming()
            ->when($city, function ($query, $city) {
                return $query->inCity($city);
            })
            ->when($skillLevel && $skillLevel !== 'all', function ($query, $skillLevel) {
                return $query->where('skill_level', $skillLevel);
            })
            ->when($matchType, function ($query, $matchType) {
                return $query->where('match_type', $matchType);
            })
            ->orderBy('match_date')
            ->paginate(12);

        $cities = TennisMatch::open()->distinct()->pluck('city')->filter()->values();
        
        return Inertia::render('matches/index', [
            'matches' => $matches,
            'cities' => $cities,
            'filters' => [
                'city' => $city,
                'skill_level' => $skillLevel,
                'match_type' => $matchType,
            ]
        ]);
    }

    /**
     * Show the form for creating a new match.
     */
    public function create()
    {
        return Inertia::render('matches/create');
    }

    /**
     * Store a newly created match.
     */
    public function store(StoreTennisMatchRequest $request)
    {
        $match = TennisMatch::create([
            'organizer_id' => auth()->id(),
            ...$request->validated()
        ]);

        return redirect()->route('matches.show', $match)
            ->with('success', 'Match created successfully!');
    }

    /**
     * Display the specified match.
     */
    public function show(TennisMatch $match)
    {
        $match->load(['organizer', 'participants.user']);
        
        $userParticipation = null;
        if (auth()->check()) {
            $userParticipation = $match->participants()
                ->where('user_id', auth()->id())
                ->first();
        }
        
        return Inertia::render('matches/show', [
            'match' => $match,
            'userParticipation' => $userParticipation
        ]);
    }

    /**
     * Show the form for editing the match.
     */
    public function edit(TennisMatch $match)
    {
        if (auth()->id() !== $match->organizer_id) {
            abort(403, 'Unauthorized');
        }
        
        return Inertia::render('matches/edit', [
            'match' => $match
        ]);
    }

    /**
     * Update the specified match.
     */
    public function update(UpdateTennisMatchRequest $request, TennisMatch $match)
    {
        if (auth()->id() !== $match->organizer_id) {
            abort(403, 'Unauthorized');
        }
        
        $match->update($request->validated());

        return redirect()->route('matches.show', $match)
            ->with('success', 'Match updated successfully!');
    }

    /**
     * Remove the specified match.
     */
    public function destroy(TennisMatch $match)
    {
        if (auth()->id() !== $match->organizer_id) {
            abort(403, 'Unauthorized');
        }
        
        $match->delete();

        return redirect()->route('matches.index')
            ->with('success', 'Match cancelled successfully!');
    }
}