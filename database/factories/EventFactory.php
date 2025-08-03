<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Event>
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $eventTypes = [
            'Tennis Tournament',
            'Mixed Doubles Championship',
            'Singles Competition',
            'Junior Tennis Camp',
            'Adult Tennis Clinic',
            'Tennis Social Event',
        ];

        return [
            'title' => fake()->randomElement($eventTypes) . ' - ' . fake()->words(2, true),
            'description' => fake()->optional()->paragraph(),
            'city' => fake()->city(),
            'venue' => fake()->company() . ' Tennis Center',
            'event_date' => fake()->dateTimeBetween('now', '+3 months'),
            'max_participants' => fake()->optional()->numberBetween(8, 64),
            'price' => fake()->optional()->randomFloat(2, 0, 100),
            'skill_level' => fake()->randomElement(['all', 'beginner', 'intermediate', 'advanced', 'pro']),
            'is_active' => fake()->boolean(90), // 90% chance of being active
        ];
    }
}