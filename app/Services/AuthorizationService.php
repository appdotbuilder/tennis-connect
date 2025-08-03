<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\TennisMatch;
use App\Models\User;

class AuthorizationService
{
    /**
     * Check if user can update profile.
     */
    public function canUpdateProfile(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id;
    }

    /**
     * Check if user can delete profile.
     */
    public function canDeleteProfile(User $user, Profile $profile): bool
    {
        return $user->id === $profile->user_id;
    }

    /**
     * Check if user can update match.
     */
    public function canUpdateMatch(User $user, TennisMatch $match): bool
    {
        return $user->id === $match->organizer_id;
    }

    /**
     * Check if user can delete match.
     */
    public function canDeleteMatch(User $user, TennisMatch $match): bool
    {
        return $user->id === $match->organizer_id;
    }
}