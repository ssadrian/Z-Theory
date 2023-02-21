<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Teacher extends Authenticatable
{
    use HasFactory, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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

    public static function createFromRequest(Request $request)
    {
        $data = $request->validate([
            'nickname' => 'required|unique:students|unique:teachers',
            'email' => 'required|email|unique:students|unique:teachers',
            'password' => 'required|confirmed',
            'name' => 'required|string',
            'surnames' => 'required|string',
            'avatar' => 'sometimes|nullable|string',
            'center' => 'required|string'
        ]);

        return Teacher::create([
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'center' => $data['center'],
            'name' => $data['name'],
            'surnames' => $data['surnames'],
            'avatar' => $data['avatar'] ?? null,
            'password' => Hash::make($data['password'])
        ]);
    }

    public static function updateFromRequest(Request $request): Teacher|null
    {
        $data = $request->validate([
            'id' => 'required|exists:teachers',
            'nickname' => 'required|string|unique:students|unique:teachers',
            'email' => 'required|email|unique:students|unique:teachers',
            'name' => 'required|string',
            'surnames' => 'required|string',
            'avatar' => 'required|string',
            'center' => 'required|string',
        ]);

        $teacher = Teacher::find($data['id']);
        $oldTeacher = $teacher;

        $teacher->nickname = $data['nickname'];
        $teacher->email = $data['email'];
        $teacher->name = $data['name'];
        $teacher->surnames = $data['surnames'];
        $teacher->center = $data['center'];
        $teacher->avatar = $data['avatar'];

        $teacher->save();

        return $oldTeacher;
    }
}
