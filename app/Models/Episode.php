<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Episode extends Model
{
    /** @use HasFactory<\Database\Factories\EpisodeFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'podcast_id',
        'title',
        'description',
        'slug',
        'audio_url',
        'duration',
        'episode_number',
        'summary',
        'release_date',
        'listen_count',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function podcast(): BelongsTo
    {
        return $this->belongsTo(Podcast::class);
    }
}
