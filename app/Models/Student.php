<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'semester',
        'class',
        'roll_no',
    ];

    /**
     * Get all votes cast by this student.
     */
    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Check if the student has already voted for a given post.
     */
    public function hasVotedFor(int $postId): bool
    {
        return $this->votes()->where('post_id', $postId)->exists();
    }

    /**
     * Get the vote record for a given post, or null if not voted.
     */
    public function getVoteFor(int $postId): ?Vote
    {
        return $this->votes()->where('post_id', $postId)->first();
    }
}
