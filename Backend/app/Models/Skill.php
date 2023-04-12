<?php

namespace App\Models;

use Illuminate\{Database\Eloquent\Factories\HasFactory,
    Database\Eloquent\Model,
    Database\Eloquent\Relations\BelongsToMany};

/**
 * @mixin IdeHelperSkill
 */
class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class);
    }
}
