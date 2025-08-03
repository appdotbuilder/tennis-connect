import React, { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

interface Event {
    id: number;
    title: string;
    description: string | null;
    city: string;
    venue: string;
    event_date: string;
    skill_level: string;
    price: number | null;
    max_participants: number | null;
}

interface Props {
    events: {
        data: Event[];
        links: Array<{ url: string | null; label: string; active: boolean }>;
    };
    cities: string[];
    filters: {
        city: string | null;
        skill_level: string | null;
    };
    [key: string]: unknown;
}

export default function EventsIndex({ events, filters }: Props) {
    const [searchCity, setSearchCity] = useState(filters.city || '');
    const [skillLevel, setSkillLevel] = useState(filters.skill_level || '');

    const handleFilter = () => {
        router.get('/events', {
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
        router.get('/events');
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('en-US', {
            month: 'long',
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
            <Head title="Tennis Events" />
            
            <div className="space-y-6">
                <div className="flex justify-between items-center">
                    <div>
                        <h1 className="text-3xl font-bold">📅 Tennis Events</h1>
                        <p className="text-gray-600 mt-2">Discover tournaments and tennis events near you</p>
                    </div>
                </div>

                {/* Filters */}
                <Card>
                    <CardHeader>
                        <CardTitle>Filter Events</CardTitle>
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
                                        <SelectItem value="all">All levels</SelectItem>
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

                {/* Events Grid */}
                <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {events.data.map((event) => (
                        <Card key={event.id} className="hover:shadow-lg transition-shadow">
                            <CardHeader>
                                <CardTitle className="text-lg">{event.title}</CardTitle>
                                <CardDescription>
                                    📍 {event.venue}, {event.city}
                                </CardDescription>
                                <CardDescription>
                                    📅 {formatDate(event.event_date)}
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <div className="flex gap-2 mb-3">
                                    <Badge className={getSkillBadgeColor(event.skill_level)}>
                                        {event.skill_level}
                                    </Badge>
                                    {event.price && (
                                        <Badge variant="secondary">${event.price}</Badge>
                                    )}
                                    {event.price === null && (
                                        <Badge variant="secondary">Free</Badge>
                                    )}
                                </div>
                                {event.description && (
                                    <p className="text-sm text-gray-600 mb-4 line-clamp-3">
                                        {event.description}
                                    </p>
                                )}
                                {event.max_participants && (
                                    <p className="text-sm text-gray-500 mb-4">
                                        Max participants: {event.max_participants}
                                    </p>
                                )}
                                <Link href={`/events/${event.id}`}>
                                    <Button variant="outline" size="sm" className="w-full">
                                        View Details
                                    </Button>
                                </Link>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                {events.data.length === 0 && (
                    <Card>
                        <CardContent className="text-center py-12">
                            <div className="text-4xl mb-4">📅</div>
                            <h3 className="text-xl font-semibold mb-2">No events found</h3>
                            <p className="text-gray-600 mb-4">Try adjusting your filters or check back later for new events.</p>
                        </CardContent>
                    </Card>
                )}

                {/* Pagination */}
                {events.links.length > 3 && (
                    <div className="flex justify-center space-x-2">
                        {events.links.map((link, index) => (
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