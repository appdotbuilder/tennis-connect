<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TennisMatchController;
use App\Http\Controllers\MatchParticipantController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

// Home page with tennis platform overview
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public routes for viewing profiles and events
Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
Route::get('/profiles/{profile}', [ProfileController::class, 'show'])->name('profiles.show');
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
Route::get('/matches', [TennisMatchController::class, 'index'])->name('matches.index');
Route::get('/matches/{match}', [TennisMatchController::class, 'show'])->name('matches.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    
    // Profile management routes
    Route::get('/profiles/create', [ProfileController::class, 'create'])->name('profiles.create');
    Route::post('/profiles', [ProfileController::class, 'store'])->name('profiles.store');
    Route::get('/profiles/{profile}/edit', [ProfileController::class, 'edit'])->name('profiles.edit');
    Route::patch('/profiles/{profile}', [ProfileController::class, 'update'])->name('profiles.update');
    Route::delete('/profiles/{profile}', [ProfileController::class, 'destroy'])->name('profiles.destroy');
    
    // Match management routes
    Route::get('/matches/create', [TennisMatchController::class, 'create'])->name('matches.create');
    Route::post('/matches', [TennisMatchController::class, 'store'])->name('matches.store');
    Route::get('/matches/{match}/edit', [TennisMatchController::class, 'edit'])->name('matches.edit');
    Route::patch('/matches/{match}', [TennisMatchController::class, 'update'])->name('matches.update');
    Route::delete('/matches/{match}', [TennisMatchController::class, 'destroy'])->name('matches.destroy');
    
    // Match participation routes
    Route::post('/matches/{match}/join', [MatchParticipantController::class, 'store'])->name('matches.join');
    Route::delete('/matches/{match}/participants/{participant}', [MatchParticipantController::class, 'destroy'])->name('matches.leave');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
