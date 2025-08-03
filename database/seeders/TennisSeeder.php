<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Profile;
use App\Models\Event;
use App\Models\TennisMatch;
use App\Models\MatchParticipant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TennisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample users
        $users = collect([
            [
                'name' => 'Alex Rodriguez',
                'email' => 'alex@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Sarah Williams',
                'email' => 'sarah@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Mike Johnson',
                'email' => 'mike@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Emma Davis',
                'email' => 'emma@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'David Chen',
                'email' => 'david@example.com',
                'password' => Hash::make('password'),
            ],
            [
                'name' => 'Lisa Thompson',
                'email' => 'lisa@example.com',
                'password' => Hash::make('password'),
            ],
        ])->map(fn($userData) => User::create($userData));

        // Create profiles for users
        $cities = ['New York', 'Los Angeles', 'Chicago', 'Houston', 'Phoenix', 'Philadelphia'];
        $skillLevels = ['beginner', 'intermediate', 'advanced', 'pro'];
        $bios = [
            'Love playing tennis on weekends. Looking for doubles partners!',
            'Tennis enthusiast since childhood. Always up for a good match.',
            'Former college player. Enjoy competitive matches and helping beginners.',
            'New to tennis but very eager to learn and improve.',
            'Weekend warrior looking for casual games and fun.',
            'Competitive player seeking challenging matches.',
        ];

        foreach ($users as $index => $user) {
            Profile::create([
                'user_id' => $user->id,
                'city' => $cities[$index],
                'skill_level' => $skillLevels[random_int(0, 3)],
                'bio' => $bios[$index],
                'availability' => ['weekends', 'evenings'],
                'looking_for_partner' => true,
            ]);
        }

        // Create sample events
        $events = [
            [
                'title' => 'Spring Tennis Tournament',
                'description' => 'Annual spring tournament for all skill levels. Great prizes and fun atmosphere!',
                'city' => 'New York',
                'venue' => 'Central Park Tennis Center',
                'event_date' => now()->addDays(random_int(7, 30)),
                'max_participants' => 32,
                'price' => 25.00,
                'skill_level' => 'all',
                'is_active' => true,
            ],
            [
                'title' => 'Beginner Friendly Match Day',
                'description' => 'Perfect for new players to get comfortable with competitive play.',
                'city' => 'Los Angeles',
                'venue' => 'Griffith Park Tennis Courts',
                'event_date' => now()->addDays(random_int(5, 25)),
                'max_participants' => 16,
                'price' => null,
                'skill_level' => 'beginner',
                'is_active' => true,
            ],
            [
                'title' => 'Advanced Players Championship',
                'description' => 'High-level competition for advanced and pro players.',
                'city' => 'Chicago',
                'venue' => 'Midtown Athletic Club',
                'event_date' => now()->addDays(random_int(10, 40)),
                'max_participants' => 24,
                'price' => 50.00,
                'skill_level' => 'advanced',
                'is_active' => true,
            ],
            [
                'title' => 'Mixed Doubles Social',
                'description' => 'Fun mixed doubles event with refreshments and networking.',
                'city' => 'Houston',
                'venue' => 'Memorial Park Tennis Center',
                'event_date' => now()->addDays(random_int(8, 35)),
                'max_participants' => 20,
                'price' => 15.00,
                'skill_level' => 'intermediate',
                'is_active' => true,
            ],
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }

        // Create sample matches
        $matches = [
            [
                'organizer_id' => $users[0]->id,
                'title' => 'Sunday Morning Doubles',
                'description' => 'Casual doubles match at the local park. All skill levels welcome!',
                'city' => 'New York',
                'venue' => 'Riverside Park Courts',
                'match_date' => now()->addDays(random_int(2, 14)),
                'max_players' => 4,
                'skill_level' => 'all',
                'match_type' => 'doubles',
                'status' => 'open',
            ],
            [
                'organizer_id' => $users[1]->id,
                'title' => 'Competitive Singles Practice',
                'description' => 'Looking for advanced players for intense singles practice.',
                'city' => 'Los Angeles',
                'venue' => 'UCLA Tennis Courts',
                'match_date' => now()->addDays(random_int(3, 16)),
                'max_players' => 2,
                'skill_level' => 'advanced',
                'match_type' => 'singles',
                'status' => 'open',
            ],
            [
                'organizer_id' => $users[2]->id,
                'title' => 'Evening Mixed Doubles',
                'description' => 'Mixed doubles after work. Great way to unwind!',
                'city' => 'Chicago',
                'venue' => 'Millennium Park Courts',
                'match_date' => now()->addDays(random_int(1, 10)),
                'max_players' => 4,
                'skill_level' => 'intermediate',
                'match_type' => 'mixed',
                'status' => 'open',
            ],
            [
                'organizer_id' => $users[3]->id,
                'title' => 'Beginner Singles Match',
                'description' => 'Perfect for new players to practice their singles game.',
                'city' => 'Houston',
                'venue' => 'Hermann Park Courts',
                'match_date' => now()->addDays(random_int(4, 18)),
                'max_players' => 2,
                'skill_level' => 'beginner',
                'match_type' => 'singles',
                'status' => 'open',
            ],
            [
                'organizer_id' => $users[4]->id,
                'title' => 'Weekend Doubles Tournament',
                'description' => 'Mini tournament format with multiple matches.',
                'city' => 'Phoenix',
                'venue' => 'Desert Ridge Tennis Club',
                'match_date' => now()->addDays(random_int(6, 20)),
                'max_players' => 8,
                'skill_level' => 'intermediate',
                'match_type' => 'doubles',
                'status' => 'open',
            ],
        ];

        foreach ($matches as $matchData) {
            $match = TennisMatch::create($matchData);
            
            // Add some participants to matches
            $participantCount = random_int(1, min(3, $match->max_players - 1));
            $availableUsers = $users->where('id', '!=', $match->organizer_id)->random($participantCount);
            
            foreach ($availableUsers as $participant) {
                MatchParticipant::create([
                    'match_id' => $match->id,
                    'user_id' => $participant->id,
                    'status' => 'confirmed',
                ]);
            }
        }
    }
}