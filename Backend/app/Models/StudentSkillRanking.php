<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSkillRanking extends Model
{
    use HasFactory;

    protected $table = 'student_skill_ranking';

    protected $fillable = [
        'student_id',
        'skill_id',
        'ranking_id'
    ];

    protected $hidden = [
        'id'
    ];

    public function student(): BelongsTo
    {
        return $this
            ->belongsTo(Student::class);
    }

    public function skill(): BelongsTo
    {
        return $this
            ->belongsTo(Skill::class);
    }

    public function ranking(): BelongsTo
    {
        return $this
            ->belongsTo(Ranking::class);
    }
}
