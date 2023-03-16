<?php

namespace App\Models;

use Illuminate\{Database\Eloquent\Factories\HasFactory, Database\Eloquent\Model};

/**
 * App\Models\DefaultAvatar
 *
 * @property int $id
 * @property string $avatar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\DefaultAvatarFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultAvatar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultAvatar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultAvatar query()
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultAvatar whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultAvatar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultAvatar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DefaultAvatar whereUpdatedAt($value)
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 */
class DefaultAvatar extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'picture'
    ];
}
