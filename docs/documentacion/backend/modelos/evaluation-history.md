<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EvaluationHistory extends Model
{
    use HasFactory;

    protected $table = "evaluation_history";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'evaluator',
        'subject',
        'skill_id',
        'ranking_id',
        'kudos'
    ];

    public function students(): BelongsToMany
    {
        return $this
            ->belongsToMany(Student::class, $this->table, 'id', 'evaluator');
    }

    public function rankings(): BelongsToMany
    {
        return null;
    }
}
