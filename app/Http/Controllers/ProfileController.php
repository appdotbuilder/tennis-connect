<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileController extends Controller
{
    /**
     * Display a listing of profiles.
     */
    public function index(Request $request)
    {
        $city = $request->get('city');
        $skillLevel = $request->get('skill_level');
        
        $profiles = Profile::with('user')
            ->lookingForPartners()
            ->when($city, function ($query, $city) {
                return $query->inCity($city);
            })
            ->when($skillLevel, function ($query, $skillLevel) {
                return $query->where('skill_level', $skillLevel);
            })
            ->latest()
            ->paginate(12);

        $cities = Profile::distinct()->pluck('city')->filter()->values();
        
        return Inertia::render('profiles/index', [
            'profiles' => $profiles,
            'cities' => $cities,
            'filters' => [
                'city' => $city,
                'skill_level' => $skillLevel,
            ]
        ]);
    }

    /**
     * Show the form for creating a new profile.
     */
    public function create()
    {
        return Inertia::render('profiles/create');
    }

    /**
     * Store a newly created profile.
     */
    public function store(StoreProfileRequest $request)
    {
        $profile = Profile::create([
            'user_id' => auth()->id(),
            ...$request->validated()
        ]);

        return redirect()->route('profiles.show', $profile)
            ->with('success', 'Profile created successfully!');
    }

    /**
     * Display the specified profile.
     */
    public function show(Profile $profile)
    {
        $profile->load('user');
        
        return Inertia::render('profiles/show', [
            'profile' => $profile
        ]);
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit(Profile $profile)
    {
        if (auth()->id() !== $profile->user_id) {
            abort(403, 'Unauthorized');
        }
        
        return Inertia::render('profiles/edit', [
            'profile' => $profile
        ]);
    }

    /**
     * Update the specified profile.
     */
    public function update(UpdateProfileRequest $request, Profile $profile)
    {
        if (auth()->id() !== $profile->user_id) {
            abort(403, 'Unauthorized');
        }
        
        $profile->update($request->validated());

        return redirect()->route('profiles.show', $profile)
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Remove the specified profile.
     */
    public function destroy(Profile $profile)
    {
        if (auth()->id() !== $profile->user_id) {
            abort(403, 'Unauthorized');
        }
        
        $profile->delete();

        return redirect()->route('profiles.index')
            ->with('success', 'Profile deleted successfully!');
    }
}