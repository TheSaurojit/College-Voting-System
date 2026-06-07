<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'voting_open',
        'results_published',
        'voting_start',
        'voting_end',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'voting_open' => 'boolean',
            'results_published' => 'boolean',
            'voting_start' => 'datetime',
            'voting_end' => 'datetime',
        ];
    }

    /**
     * Get the current election settings, or create defaults if none exist.
     */
    public static function current(): static
    {
        return static::first() ?? static::create([
            'title' => 'Students Union Election',
            'voting_open' => false,
            'results_published' => false,
        ]);
    }
}
