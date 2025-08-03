<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\TennisMatch
 *
 * @property int $id
 * @property int $organizer_id
 * @property string $title
 * @property string|null $description
 * @property string $city
 * @property string $venue
 * @property \Illuminate\Support\Carbon $match_date
 * @property int $max_players
 * @property string $skill_level
 * @property string $match_type
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $organizer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\MatchParticipant> $participants
 * @property-read int|null $participants_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch query()
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereMatchDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereMatchType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereMaxPlayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereOrganizerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereSkillLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch whereVenue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch open()
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch upcoming()
 * @method static \Illuminate\Database\Eloquent\Builder|TennisMatch inCity($city)
 * @method static \Database\Factories\TennisMatchFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class TennisMatch extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'matches';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'city',
        'venue',
        'match_date',
        'max_players',
        'skill_level',
        'match_type',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'match_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the organizer of the match.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    /**
     * Get the participants for the match.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants(): HasMany
    {
        return $this->hasMany(MatchParticipant::class, 'match_id');
    }

    /**
     * Scope a query to only include open matches.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope a query to only include upcoming matches.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query)
    {
        return $query->where('match_date', '>', now());
    }

    /**
     * Scope a query to filter by city.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $city
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInCity($query, $city)
    {
        return $query->where('city', 'like', '%' . $city . '%');
    }
}