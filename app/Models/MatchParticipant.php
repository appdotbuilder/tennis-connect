<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\MatchParticipant
 *
 * @property int $id
 * @property int $match_id
 * @property int $user_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TennisMatch $match
 * @property-read \App\Models\User $user
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|MatchParticipant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchParticipant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchParticipant query()
 * @method static \Illuminate\Database\Eloquent\Builder|MatchParticipant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchParticipant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchParticipant whereMatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchParticipant whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchParticipant whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchParticipant whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MatchParticipant confirmed()
 * @method static \Database\Factories\MatchParticipantFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class MatchParticipant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'match_id',
        'user_id',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the match that this participant belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function match(): BelongsTo
    {
        return $this->belongsTo(TennisMatch::class, 'match_id');
    }

    /**
     * Get the user that is participating.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include confirmed participants.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }
}