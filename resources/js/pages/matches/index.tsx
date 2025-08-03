import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

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

interface Props {
    matches: {
        data: TennisMatch[];
        links: Array<{ url: string | null; label: string; active: boolean }>;
    };
    cities: string[];
    filters: {
        city: string | null;
        skill_level: string | null;
        match_type: string | null;
    };
    [key: string]: unknown;
}

export default function MatchesIndex({ matches, filters }: Props) {
    const [searchCity, setSearchCity] = useState(filters.city || '');
    const [skillLevel, setSkillLevel] = useState(filters.skill_level || '');
    const [matchType, setMatchType] = useState(filters.match_type || '');

    const handleFilter = () => {
        router.get('/matches', {
            city: searchCity,
            skill_level: skillLevel,
            match_type: matchType,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const clearFilters = () => {
        setSearchCity('');
        setSkillLevel('');
        setMatchType('');
        router.get('/matches');
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
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
            case 'all': return 'bg-gray-100 text-gray-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    };

    return (
        <AppShell>
            <Head title="Tennis Matches" />
            
            <div className="space-y-6">
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-3xl font-bold">🏆 Tennis Matches</h1>
                        <p className="text-gray-600 mt-2">Find and join tennis matches in your area</p>
                    </div>
                    <Link href="/matches/create">
                        <Button>Organize Match</Button>
                    </Link>
                </div>

                {/* Filters */}
                <Card>
                    <CardHeader>
                        <CardTitle>Filter Matches</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label className="block text-sm font-medium mb-2">City</label>
                                <Input
                                    placeholder="Search by city..."
                                    value={searchCity}
                                    onChange={(e) => setSearchCity(e.target.value)}
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-2">Skill Level</label>
                                <Select value={skillLevel} onValueChange={setSkillLevel}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Any skill level" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="">Any skill level</SelectItem>
                                        <SelectItem value="all">All levels</SelectItem>
                                        <SelectItem value="beginner">Beginner</SelectItem>
                                        <SelectItem value="intermediate">Intermediate</SelectItem>
                                        <SelectItem value="advanced">Advanced</SelectItem>
                                        <SelectItem value="pro">Pro</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div>
                                <label className="block text-sm font-medium mb-2">Match Type</label>
                                <Select value={matchType} onValueChange={setMatchType}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Any type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="">Any type</SelectItem>
                                        <SelectItem value="singles">Singles</SelectItem>
                                        <SelectItem value="doubles">Doubles</SelectItem>
                                        <SelectItem value="mixed">Mixed</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="flex items-end gap-2">
                                <Button onClick={handleFilter} className="flex-1">Apply</Button>
                                <Button variant="outline" onClick={clearFilters}>Clear</Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Matches Grid */}
                <div className="grid md:grid-cols-2 gap-6">
                    {matches.data.map((match) => (
                        <Card key={match.id} className="hover:shadow-lg transition-shadow">
                            <CardHeader>
                                <div className="flex justify-between items-start">
                                    <div className="flex-1">
                                        <CardTitle className="text-lg">{match.title}</CardTitle>
                                        <CardDescription className="mt-1">
                                            📍 {match.venue}, {match.city}
                                        </CardDescription>
                                        <CardDescription className="mt-1">
                                            📅 {formatDate(match.match_date)}
                                        </CardDescription>
                                    </div>
                                    <Badge variant="outline" className="ml-2">
                                        {match.participants.length}/{match.max_players}
                                    </Badge>
                                </div>
                            </CardHeader>
                            <CardContent>
                                <div className="flex gap-2 mb-3">
                                    <Badge className={getSkillBadgeColor(match.skill_level)}>
                                        {match.skill_level}
                                    </Badge>
                                    <Badge variant="secondary">{match.match_type}</Badge>
                                </div>
                                <p className="text-sm text-gray-600 mb-4">
                                    Organized by {match.organizer.name}
                                </p>
                                <Link href={`/matches/${match.id}`}>
                                    <Button variant="outline" size="sm" className="w-full">
                                        View Details
                                    </Button>
                                </Link>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                {matches.data.length === 0 && (
                    <Card>
                        <CardContent className="text-center py-12">
                            <div className="text-4xl mb-4">🏆</div>
                            <h3 className="text-xl font-semibold mb-2">No matches found</h3>
                            <p className="text-gray-600 mb-4">Try adjusting your filters or organize your own match.</p>
                            <Link href="/matches/create">
                                <Button>Organize a Match</Button>
                            </Link>
                        </CardContent>
                    </Card>
                )}

                {/* Pagination */}
                {matches.links.length > 3 && (
                    <div className="flex justify-center space-x-2">
                        {matches.links.map((link, index) => (
                            <Button
                                key={index}
                                variant={link.active ? "default" : "outline"}
                                size="sm"
                                disabled={!link.url}
                                onClick={() => link.url && router.get(link.url)}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        ))}
                    </div>
                )}
            </div>
        </AppShell>
    );
}