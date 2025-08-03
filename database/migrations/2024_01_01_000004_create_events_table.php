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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Event title');
            $table->text('description')->nullable()->comment('Event description');
            $table->string('city')->comment('Event location city');
            $table->string('venue')->comment('Event venue name');
            $table->datetime('event_date')->comment('Date and time of the event');
            $table->integer('max_participants')->nullable()->comment('Maximum number of participants');
            $table->decimal('price', 8, 2)->nullable()->comment('Event price if any');
            $table->enum('skill_level', ['all', 'beginner', 'intermediate', 'advanced', 'pro'])->default('all');
            $table->boolean('is_active')->default(true)->comment('Whether event is active/visible');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('city');
            $table->index('event_date');
            $table->index('skill_level');
            $table->index('is_active');
            $table->index(['city', 'event_date', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};