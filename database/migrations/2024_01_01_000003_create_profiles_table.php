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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('city')->comment('User\'s city location');
            $table->enum('skill_level', ['beginner', 'intermediate', 'advanced', 'pro'])->default('beginner');
            $table->text('bio')->nullable()->comment('User\'s bio description');
            $table->json('availability')->nullable()->comment('Available days/times in JSON format');
            $table->boolean('looking_for_partner')->default(true)->comment('Whether user is looking for playing partners');
            $table->timestamps();
            
            // Indexes for performance
            $table->index('city');
            $table->index('skill_level');
            $table->index('looking_for_partner');
            $table->index(['city', 'looking_for_partner']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};