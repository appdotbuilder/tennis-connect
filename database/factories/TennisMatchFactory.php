<?php

namespace Database\Factories;

use App\Models\TennisMatch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TennisMatch>
 */
class TennisMatchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\TennisMatch>
     */
    protected $model = TennisMatch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $matchTitles = [
            'Sunday Morning Tennis',
            'Evening Doubles Match',
            'Competitive Singles',
            'Casual Tennis Game',
            'Weekend Tournament',
            'Tennis Practice Session',
        ];

        return [
            'organizer_id' => User::factory(),
            'title' => fake()->randomElement($matchTitles),
            'description' => fake()->optional()->paragraph(),
            'city' => fake()->city(),
            'venue' => fake()->streetName() . ' Tennis Courts',
            'match_date' => fake()->dateTimeBetween('now', '+2 months'),
            'max_players' => fake()->randomElement([2, 4, 6, 8]),
            'skill_level' => fake()->randomElement(['all', 'beginner', 'intermediate', 'advanced', 'pro']),
            'match_type' => fake()->randomElement(['singles', 'doubles', 'mixed']),
            'status' => fake()->randomElement(['open', 'full']),
        ];
    }
}