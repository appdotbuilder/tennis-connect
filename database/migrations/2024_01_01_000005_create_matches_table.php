<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->string('title')->comment('Match title');
            $table->text('description')->nullable()->comment('Match description');
            $table->string('city')->comment('Match location city');
            $table->string('venue')->comment('Match venue');
            $table->datetime('match_date')->comment('Date and time of the match');
            $table->integer('max_players')->default(4)->comment('Maximum number of players');
            $table->enum('skill_level', ['all', 'beginner', 'intermediate', 'advanced', 'pro'])->default('all');
            $table->enum('match_type', ['singles', 'doubles', 'mixed'])->default('singles');
            $table->enum('status', ['open', 'full', 'completed', 'cancelled'])->default('open');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('organizer_id');
            $table->index('city');
            $table->index('match_date');
            $table->index('skill_level');
            $table->index('status');
            $table->index(['city', 'match_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};