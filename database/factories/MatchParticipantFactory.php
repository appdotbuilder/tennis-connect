<?php

namespace Database\Factories;

use App\Models\MatchParticipant;
use App\Models\TennisMatch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MatchParticipant>
 */
class MatchParticipantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\MatchParticipant>
     */
    protected $model = MatchParticipant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'match_id' => TennisMatch::factory(),
            'user_id' => User::factory(),
            'status' => fake()->randomElement(['pending', 'confirmed', 'declined']),
        ];
    }
}