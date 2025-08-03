import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

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

interface Props {
    profiles: {
        data: Profile[];
        links: Array<{ url: string | null; label: string; active: boolean }>;
    };
    cities: string[];
    filters: {
        city: string | null;
        skill_level: string | null;
    };
    [key: string]: unknown;
}

export default function ProfilesIndex({ profiles, filters }: Props) {
    const [searchCity, setSearchCity] = useState(filters.city || '');
    const [skillLevel, setSkillLevel] = useState(filters.skill_level || '');

    const handleFilter = () => {
        router.get('/profiles', {
            city: searchCity,
            skill_level: skillLevel,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const clearFilters = () => {
        setSearchCity('');
        setSkillLevel('');
        router.get('/profiles');
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
        <AppShell>
            <Head title="Find Tennis Partners" />
            
            <div className="space-y-6">
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-3xl font-bold">👥 Find Tennis Partners</h1>
                        <p className="text-gray-600 mt-2">Connect with tennis players in your area</p>
                    </div>
                    <Link href="/profiles/create">
                        <Button>Create Profile</Button>
                    </Link>
                </div>

                {/* Filters */}
                <Card>
                    <CardHeader>
                        <CardTitle>Filter Players</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                                        <SelectItem value="beginner">Beginner</SelectItem>
                                        <SelectItem value="intermediate">Intermediate</SelectItem>
                                        <SelectItem value="advanced">Advanced</SelectItem>
                                        <SelectItem value="pro">Pro</SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                            <div className="flex items-end gap-2">
                                <Button onClick={handleFilter}>Apply Filters</Button>
                                <Button variant="outline" onClick={clearFilters}>Clear</Button>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {/* Profiles Grid */}
                <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {profiles.data.map((profile) => (
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
                            <CardContent>
                                {profile.bio && (
                                    <p className="text-sm text-gray-600 mb-4 line-clamp-3">{profile.bio}</p>
                                )}
                                <Link href={`/profiles/${profile.id}`}>
                                    <Button variant="outline" size="sm" className="w-full">
                                        View Profile
                                    </Button>
                                </Link>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                {profiles.data.length === 0 && (
                    <Card>
                        <CardContent className="text-center py-12">
                            <div className="text-4xl mb-4">🎾</div>
                            <h3 className="text-xl font-semibold mb-2">No players found</h3>
                            <p className="text-gray-600 mb-4">Try adjusting your filters or check back later for new players.</p>
                            <Link href="/profiles/create">
                                <Button>Create Your Profile</Button>
                            </Link>
                        </CardContent>
                    </Card>
                )}

                {/* Pagination */}
                {profiles.links.length > 3 && (
                    <div className="flex justify-center space-x-2">
                        {profiles.links.map((link, index) => (
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