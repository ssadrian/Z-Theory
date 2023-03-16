<?php

namespace App\Models;

use Illuminate\{
    Database\Eloquent\Factories\HasFactory,
    Database\Eloquent\Model,
    Database\Eloquent\Relations\BelongsTo,
    Database\Eloquent\Relations\BelongsToMany
};

/**
 * App\Models\Ranking
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property \App\Models\Teacher $creator
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $queues
 * @property-read int|null $queues_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @method static \Database\Factories\RankingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereCreator($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ranking whereUpdatedAt($value)
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */
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

    /**
     * All the relationships to be touched.
     *
     * @var array
     */
    protected $touches = [
        'students',
        'queues',
        'assignments'
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
