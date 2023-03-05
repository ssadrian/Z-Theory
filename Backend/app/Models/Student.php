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

    public static function createFromRequest(Request $request): Student
    {
        $data = $request->validate([
            'nickname' => 'required|string|unique:students|unique:teachers',
            'name' => 'required|string',
            'surnames' => 'required|string',
            'email' => 'required|email|unique:students|unique:teachers',
            'password' => 'required|confirmed',
            'birth_date' => 'required|date',
            'avatar' => 'sometimes|string'
        ]);

        return Student::create([
            'nickname' => $data['nickname'],
            'name' => $data['name'],
            'surnames' => $data['surnames'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'avatar' => $data['avatar'] ?? null,
            'birth_date' => $data['birth_date'],
        ]);
    }

    public static function updateFromRequest($id, Request $request): array|null
    {
        $data = $request->validate([
            'nickname' => 'sometimes|nullable|required|string',
            'name' => 'sometimes|nullable|required|string',
            'surnames' => 'sometimes|nullable|required|string',
            'birth_date' => 'sometimes|nullable|required|date',
            'avatar' => 'sometimes|nullable|required|string',
        ]);

        $student = Student::find($id);
        if (!$student) {
            return null;
        }

        if (!(empty($data['nickname']) || $student->nickname == $data['nickname'])) {
            $student->nickname = $data['nickname'];
        }

        if (!empty($data['name'])) {
            $student->name = $data['name'];
        }

        if (!empty($data['surnames'])) {
            $student->surnames = $data['surnames'];
        }

        if (!empty($data['birth_date'])) {
            $student->birth_date = $data['birth_date'];
        }

        if (!empty($data['avatar'])) {
            $student->avatar = $data['avatar'];
        }

        $student->save();

        return $student->getOriginal();
    }

    public function rankings(): BelongsToMany
    {
        return $this
            ->belongsToMany(Ranking::class, 'ranking_student', 'student_id', 'ranking_id')
            ->withPivot('points');
    }
}
