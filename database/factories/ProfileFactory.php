<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\Profile>
     */
    protected $model = Profile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'city' => fake()->city(),
            'skill_level' => fake()->randomElement(['beginner', 'intermediate', 'advanced', 'pro']),
            'bio' => fake()->optional()->paragraph(),
            'availability' => fake()->randomElements(['weekdays', 'weekends', 'mornings', 'evenings'], fake()->numberBetween(1, 3)),
            'looking_for_partner' => fake()->boolean(80), // 80% chance of looking for partner
        ];
    }
}