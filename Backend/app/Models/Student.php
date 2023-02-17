<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function rankings(): HasMany
    {
        return $this->hasMany(Ranking::class);
    }

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

    public static function updateFromRequest(Request $request): Student|null
    {
        $data = $request->validate([
            'id' => 'required|exists:students',
            'nickname' => 'required|string|unique:students|unique:teachers',
            'name' => 'required|string',
            'surnames' => 'required|string',
            'email' => 'required|email|unique:students|unique:teachers',
            'birth_date' => 'required|date',
            'avatar' => 'required|string',
        ]);

        $student = Student::find($data['id']);
        $oldStudent = $student;

        $student->nickname = $data['nickname'];
        $student->email = $data['email'];
        $student->name = $data['name'];
        $student->surnames = $data['surnames'];
        $student->birth_date = $data['birth_date'];
        $student->avatar = $data['avatar'];

        $student->save();

        return $oldStudent;
    }
}
