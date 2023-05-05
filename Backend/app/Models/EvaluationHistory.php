<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    protected $hidden = [
        'subject.password'
    ];

    public function evaluator(): HasOne
    {
        return $this
            ->hasOne(Student::class, 'id', 'evaluator');
    }

    public function subject(): HasOne
    {
        return $this
            ->hasOne(Student::class, 'id', 'subject');
    }

    public function ranking(): HasOne
    {
        return $this
            ->hasOne(Ranking::class, 'id', 'ranking_id');
    }

    public function skill(): HasOne
    {
        return $this
            ->hasOne(Skill::class, 'id', 'skill_id');
    }
}
