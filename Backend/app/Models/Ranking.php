<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ranking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'creator'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
    ];

    public function students(): BelongsToMany
    {
        return $this
            ->belongsToMany(Student::class, 'ranking_student',
                'ranking_id', 'student_id')
            ->withPivot('points');
    }

    public function creator(): BelongsTo
    {
        return $this
            ->belongsTo(Teacher::class, 'creator');
    }

    public function queues(): BelongsToMany
    {
        return $this
            ->belongsToMany(Student::class, 'ranking_join_queue',
                'ranking_id', 'student_id')
            ->withPivot('join_status_id')
            ->join('join_statuses',
                'join_statuses.id', '=', 'ranking_join_queue.join_status_id');
    }

    public function assignments(): BelongsToMany
    {
        return $this
            ->belongsToMany(Assignment::class, 'ranking_assignment');
    }
}
