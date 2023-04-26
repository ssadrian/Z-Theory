<?php

namespace App\Models;

use Illuminate\{Database\Eloquent\Factories\HasFactory,
    Database\Eloquent\Relations\BelongsToMany,
    Foundation\Auth\User as Authenticatable};
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\Student
 *
 * @property int $id
 * @property string $name
 * @property string $surnames
 * @property string $email
 * @property string $password
 * @property string $nickname
 * @property string|null $avatar
 * @property string $birth_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ranking> $rankings
 * @property-read int|null $rankings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\StudentFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereSurnames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereUpdatedAt($value)
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 * @mixin IdeHelperStudent
 */
class Student extends Authenticatable
{
    use HasFactory, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nickname',
        'avatar',
        'email',
        'password',
        'name',
        'surnames',
        'birth_date'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function rankings(): BelongsToMany
    {
        return $this
            ->belongsToMany(Ranking::class)
            ->withPivot(['points', 'kudos']);
    }

    public function assignments(): BelongsToMany
    {
        return $this
            ->belongsToMany(Assignment::class)
            ->withPivot(['status', 'mark']);
    }

    public function skills(): BelongsToMany
    {
        return $this
            ->belongsToMany(Skill::class, 'student_skill')
            ->withPivot(['kudos', 'image']);
    }

    public function evaluationHistory(): BelongsToMany {
        return $this
            ->belongsToMany(Skill::class, 'evaluation_history', 'evaluator')
            ->withPivot(['id', 'subject', 'ranking_id', 'kudos']);
    }
}
