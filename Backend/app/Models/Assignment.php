<?php

namespace App\Models;

use App\Traits\CanUpdate;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Assignment extends Model
{
    use HasFactory, CanUpdate;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'content',
        'points',
        'teacher_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'teacher_id',
    ];

    /**
     * All the relationships to be touched.
     *
     * @var array
     */
    protected $touches = [
        'rankingsAssigned'
    ];

    public function creator(): BelongsTo
    {
        return $this
            ->belongsTo(Teacher::class, 'teacher_id');
    }

    public function rankingsAssigned(): BelongsToMany
    {
        return $this
            ->belongsToMany(Ranking::class, 'ranking_assignment');
    }
}
