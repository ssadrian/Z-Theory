<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

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
        'birth_date',
    ];

    public function rankings(): BelongsToMany
    {
        return $this
            ->belongsToMany(Ranking::class, 'ranking_student', 'student_id', 'ranking_id')
            ->withPivot('points');
    }
}
