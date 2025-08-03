<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TennisMatch;
use App\Models\MatchParticipant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MatchParticipantController extends Controller
{
    /**
     * Join a tennis match.
     */
    public function store(Request $request, TennisMatch $match)
    {
        $user = auth()->user();
        
        // Check if user is already participating
        $existingParticipation = $match->participants()
            ->where('user_id', $user->id)
            ->first();
            
        if ($existingParticipation) {
            return back()->with('error', 'You are already registered for this match.');
        }
        
        // Check if match is full
        $currentParticipants = $match->participants()->where('status', 'confirmed')->count();
        if ($currentParticipants >= $match->max_players) {
            return back()->with('error', 'This match is already full.');
        }
        
        MatchParticipant::create([
            'match_id' => $match->id,
            'user_id' => $user->id,
            'status' => 'confirmed'
        ]);
        
        // Update match status if full
        if ($currentParticipants + 1 >= $match->max_players) {
            $match->update(['status' => 'full']);
        }
        
        return back()->with('success', 'Successfully joined the match!');
    }

    /**
     * Leave a tennis match.
     */
    public function destroy(TennisMatch $match, MatchParticipant $participant)
    {
        $user = auth()->user();
        
        // Check if user can leave this match
        if ($participant->user_id !== $user->id) {
            return back()->with('error', 'You can only leave matches you joined.');
        }
        
        $participant->delete();
        
        // Update match status if it was full
        if ($match->status === 'full') {
            $match->update(['status' => 'open']);
        }
        
        return back()->with('success', 'You have left the match.');
    }
}