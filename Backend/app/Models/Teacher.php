<?php

namespace App\Models;

use Illuminate\{
    Database\Eloquent\Factories\HasFactory,
    Database\Eloquent\Relations\HasMany,
    Foundation\Auth\User as Authenticatable
};
use Laravel\Sanctum\HasApiTokens;

/**
 * App\Models\Teacher
 *
 * @property int $id
 * @property string $name
 * @property string $surnames
 * @property string $email
 * @property string $password
 * @property string $nickname
 * @property string|null $avatar
 * @property string $center
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ranking> $rankingsCreated
 * @property-read int|null $rankings_created_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\TeacherFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher query()
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereCenter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereSurnames($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Teacher whereUpdatedAt($value)
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 * @mixin IdeHelperTeacher
 */
class Teacher extends Authenticatable
{
    use HasFactory, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'nickname',
        'email',
        'password',
        'name',
        'surnames',
        'avatar',
        'center',
    ];

    /**
     * All the relationships to be touched.
     *
     * @var array<string>
     */
    protected $touches = [
        'rankingsCreated'
    ];

    /**
     * Abilities for a token issued to a teacher
     *
     * @var array<string>
     */
    public $abilities = [
        'index:assignments',
        'store:rankings'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    public function rankingsCreated(): HasMany
    {
        return $this->hasMany(Ranking::class, foreignKey: 'creator');
    }
}
