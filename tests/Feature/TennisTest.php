<?php

use App\Models\User;
use App\Models\Profile;
use App\Models\Event;
use App\Models\TennisMatch;
use App\Models\MatchParticipant;

test('home page displays tennis platform', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->component('welcome')
            ->has('recentProfiles')
            ->has('upcomingEvents')
            ->has('upcomingMatches')
            ->has('stats')
    );
});

test('profiles page can be accessed', function () {
    $response = $this->get('/profiles');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->component('profiles/index')
            ->has('profiles')
            ->has('cities')
            ->has('filters')
    );
});

test('events page can be accessed', function () {
    $response = $this->get('/events');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->component('events/index')
            ->has('events')
            ->has('cities')
            ->has('filters')
    );
});

test('matches page can be accessed', function () {
    $response = $this->get('/matches');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->component('matches/index')
            ->has('matches')
            ->has('cities')
            ->has('filters')
    );
});

test('user can create profile', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/profiles', [
        'city' => 'New York',
        'skill_level' => 'intermediate',
        'bio' => 'Love playing tennis!',
        'looking_for_partner' => true,
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('profiles', [
        'user_id' => $user->id,
        'city' => 'New York',
        'skill_level' => 'intermediate',
    ]);
});

test('user can create match', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/matches', [
        'title' => 'Test Match',
        'city' => 'Los Angeles',
        'venue' => 'Test Courts',
        'match_date' => now()->addDay()->format('Y-m-d H:i:s'),
        'max_players' => 4,
        'skill_level' => 'all',
        'match_type' => 'doubles',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('matches', [
        'organizer_id' => $user->id,
        'title' => 'Test Match',
        'city' => 'Los Angeles',
    ]);
});

test('user can join match', function () {
    $organizer = User::factory()->create();
    $participant = User::factory()->create();
    
    $match = TennisMatch::factory()->create([
        'organizer_id' => $organizer->id,
        'max_players' => 4,
        'status' => 'open',
    ]);

    $response = $this->actingAs($participant)->post("/matches/{$match->id}/join");

    $response->assertRedirect();
    $this->assertDatabaseHas('match_participants', [
        'match_id' => $match->id,
        'user_id' => $participant->id,
        'status' => 'confirmed',
    ]);
});

test('profile filtering by city works', function () {
    Profile::factory()->create(['city' => 'New York', 'looking_for_partner' => true]);
    Profile::factory()->create(['city' => 'Los Angeles', 'looking_for_partner' => true]);

    $response = $this->get('/profiles?city=New York');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->where('filters.city', 'New York')
    );
});

test('match filtering by skill level works', function () {
    TennisMatch::factory()->create(['skill_level' => 'beginner', 'status' => 'open']);
    TennisMatch::factory()->create(['skill_level' => 'advanced', 'status' => 'open']);

    $response = $this->get('/matches?skill_level=beginner');

    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => 
        $page->where('filters.skill_level', 'beginner')
    );
});