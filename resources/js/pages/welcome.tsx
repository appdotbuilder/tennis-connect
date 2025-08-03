import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';

interface Profile {
    id: number;
    city: string;
    skill_level: string;
    bio: string | null;
    user: {
        id: number;
        name: string;
    };
}

interface Event {
    id: number;
    title: string;
    city: string;
    venue: string;
    event_date: string;
    skill_level: string;
}

interface TennisMatch {
    id: number;
    title: string;
    city: string;
    venue: string;
    match_date: string;
    skill_level: string;
    match_type: string;
    max_players: number;
    organizer: {
        name: string;
    };
    participants: Array<{ id: number }>;
}

interface Stats {
    profiles_count: number;
    events_count: number;
    matches_count: number;
}

interface Props {
    recentProfiles: Profile[];
    upcomingEvents: Event[];
    upcomingMatches: TennisMatch[];
    stats: Stats;
    auth?: {
        user?: {
            id: number;
            name: string;
            email: string;
        };
    };
    [key: string]: unknown;
}

export default function Welcome({ recentProfiles, upcomingMatches, stats, auth }: Props) {
    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    const getSkillBadgeColor = (skill: string) => {
        switch (skill) {
            case 'beginner': return 'bg-green-100 text-green-800';
            case 'intermediate': return 'bg-blue-100 text-blue-800';
            case 'advanced': return 'bg-purple-100 text-purple-800';
            case 'pro': return 'bg-red-100 text-red-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    };

    return (
        <>
            <Head title="TennisConnect - Find Partners & Organize Matches" />
            
            <div className="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50">
                {/* Header */}
                <header className="border-b bg-white/80 backdrop-blur-sm">
                    <div className="container mx-auto px-4 py-4">
                        <div className="flex justify-between items-center">
                            <div className="flex items-center space-x-2">
                                <div className="text-2xl">🎾</div>
                                <h1 className="text-2xl font-bold text-gray-900">TennisConnect</h1>
                            </div>
                            <div className="flex items-center space-x-4">
                                {auth?.user ? (
                                    <>
                                        <Link href="/dashboard" className="text-gray-600 hover:text-gray-900">
                                            Dashboard
                                        </Link>
                                        <span className="text-gray-600">Welcome, {auth.user.name}!</span>
                                    </>
                                ) : (
                                    <>
                                        <Link href="/login">
                                            <Button variant="ghost">Login</Button>
                                        </Link>
                                        <Link href="/register">
                                            <Button>Get Started</Button>
                                        </Link>
                                    </>
                                )}
                            </div>
                        </div>
                    </div>
                </header>

                {/* Hero Section */}
                <section className="py-20 px-4">
                    <div className="container mx-auto text-center">
                        <div className="max-w-4xl mx-auto">
                            <h2 className="text-5xl font-bold text-gray-900 mb-6">
                                🎾 Connect with Tennis Players
                                <br />
                                <span className="text-blue-600">Near You</span>
                            </h2>
                            <p className="text-xl text-gray-600 mb-8 leading-relaxed">
                                Find playing partners, join exciting tournaments, and organize your own matches. 
                                Whether you're a beginner or a pro, TennisConnect helps you stay active and meet fellow tennis enthusiasts.
                            </p>
                            
                            {/* Stats */}
                            <div className="grid grid-cols-3 gap-8 mb-10">
                                <div className="text-center">
                                    <div className="text-3xl font-bold text-blue-600">{stats.profiles_count}</div>
                                    <div className="text-gray-600">Active Players</div>
                                </div>
                                <div className="text-center">
                                    <div className="text-3xl font-bold text-green-600">{stats.events_count}</div>
                                    <div className="text-gray-600">Upcoming Events</div>
                                </div>
                                <div className="text-center">
                                    <div className="text-3xl font-bold text-purple-600">{stats.matches_count}</div>
                                    <div className="text-gray-600">Open Matches</div>
                                </div>
                            </div>

                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <Link href="/profiles">
                                    <Button size="lg" className="text-lg px-8">
                                        👥 Find Playing Partners
                                    </Button>
                                </Link>
                                <Link href="/matches">
                                    <Button size="lg" variant="outline" className="text-lg px-8">
                                        🏆 Browse Matches
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Features Section */}
                <section className="py-16 px-4 bg-white">
                    <div className="container mx-auto">
                        <h3 className="text-3xl font-bold text-center mb-12">Everything You Need to Play Tennis</h3>
                        <div className="grid md:grid-cols-3 gap-8">
                            <div className="text-center p-6">
                                <div className="text-4xl mb-4">👥</div>
                                <h4 className="text-xl font-semibold mb-2">Find Partners</h4>
                                <p className="text-gray-600">Search players by city and skill level. View profiles to find your perfect playing partner.</p>
                            </div>
                            <div className="text-center p-6">
                                <div className="text-4xl mb-4">📅</div>
                                <h4 className="text-xl font-semibold mb-2">Join Events</h4>
                                <p className="text-gray-600">Discover tournaments and tennis events happening in your area. From casual games to competitive matches.</p>
                            </div>
                            <div className="text-center p-6">
                                <div className="text-4xl mb-4">🏆</div>
                                <h4 className="text-xl font-semibold mb-2">Organize Matches</h4>
                                <p className="text-gray-600">Create your own matches, set the details, and invite other players to join your games.</p>
                            </div>
                        </div>
                    </div>
                </section>

                {/* Recent Profiles */}
                {recentProfiles.length > 0 && (
                    <section className="py-16 px-4">
                        <div className="container mx-auto">
                            <div className="flex justify-between items-center mb-8">
                                <h3 className="text-2xl font-bold">👥 Recent Players</h3>
                                <Link href="/profiles">
                                    <Button variant="outline">View All Profiles</Button>
                                </Link>
                            </div>
                            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                                {recentProfiles.map((profile) => (
                                    <Card key={profile.id} className="hover:shadow-lg transition-shadow">
                                        <CardHeader>
                                            <div className="flex justify-between items-start">
                                                <div>
                                                    <CardTitle className="text-lg">{profile.user.name}</CardTitle>
                                                    <CardDescription>📍 {profile.city}</CardDescription>
                                                </div>
                                                <Badge className={getSkillBadgeColor(profile.skill_level)}>
                                                    {profile.skill_level}
                                                </Badge>
                                            </div>
                                        </CardHeader>
                                        {profile.bio && (
                                            <CardContent>
                                                <p className="text-sm text-gray-600 line-clamp-2">{profile.bio}</p>
                                            </CardContent>
                                        )}
                                    </Card>
                                ))}
                            </div>
                        </div>
                    </section>
                )}

                {/* Upcoming Matches */}
                {upcomingMatches.length > 0 && (
                    <section className="py-16 px-4 bg-gray-50">
                        <div className="container mx-auto">
                            <div className="flex justify-between items-center mb-8">
                                <h3 className="text-2xl font-bold">🏆 Upcoming Matches</h3>
                                <Link href="/matches">
                                    <Button variant="outline">View All Matches</Button>
                                </Link>
                            </div>
                            <div className="grid md:grid-cols-2 gap-6">
                                {upcomingMatches.map((match) => (
                                    <Card key={match.id} className="hover:shadow-lg transition-shadow">
                                        <CardHeader>
                                            <CardTitle className="flex justify-between items-start">
                                                <span>{match.title}</span>
                                                <Badge variant="outline">
                                                    {match.participants.length}/{match.max_players}
                                                </Badge>
                                            </CardTitle>
                                            <CardDescription>
                                                📍 {match.venue}, {match.city} • 📅 {formatDate(match.match_date)}
                                            </CardDescription>
                                        </CardHeader>
                                        <CardContent>
                                            <div className="flex gap-2 mb-2">
                                                <Badge className={getSkillBadgeColor(match.skill_level)}>
                                                    {match.skill_level}
                                                </Badge>
                                                <Badge variant="secondary">{match.match_type}</Badge>
                                            </div>
                                            <p className="text-sm text-gray-600">Organized by {match.organizer.name}</p>
                                        </CardContent>
                                    </Card>
                                ))}
                            </div>
                        </div>
                    </section>
                )}

                {/* Call to Action */}
                <section className="py-20 px-4 bg-blue-600 text-white">
                    <div className="container mx-auto text-center">
                        <h3 className="text-3xl font-bold mb-4">Ready to Play Tennis?</h3>
                        <p className="text-xl mb-8 opacity-90">Join thousands of tennis players connecting and playing together.</p>
                        {!auth?.user && (
                            <div className="flex flex-col sm:flex-row gap-4 justify-center">
                                <Link href="/register">
                                    <Button size="lg" variant="secondary" className="text-lg px-8">
                                        🎾 Join TennisConnect
                                    </Button>
                                </Link>
                                <Link href="/login">
                                    <Button size="lg" variant="outline" className="text-lg px-8 text-white border-white hover:bg-white hover:text-blue-600">
                                        Sign In
                                    </Button>
                                </Link>
                            </div>
                        )}
                        {auth?.user && (
                            <Link href="/profiles/create">
                                <Button size="lg" variant="secondary" className="text-lg px-8">
                                    🎾 Create Your Profile
                                </Button>
                            </Link>
                        )}
                    </div>
                </section>

                {/* Footer */}
                <footer className="py-8 px-4 bg-gray-900 text-white">
                    <div className="container mx-auto text-center">
                        <div className="flex items-center justify-center space-x-2 mb-4">
                            <div className="text-2xl">🎾</div>
                            <span className="text-xl font-bold">TennisConnect</span>
                        </div>
                        <p className="text-gray-400">Connecting tennis players worldwide. Play more, connect more.</p>
                    </div>
                </footer>
            </div>
        </>
    );
}