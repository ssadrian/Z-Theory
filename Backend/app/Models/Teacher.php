<?php

namespace App\Models;

use Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use LaravelIdea\Helper\App\Models\_IH_Teacher_C;

class Teacher extends Model
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
        'center',
    ];

    public static function createFromRequest(Request $request)
    {
        $data = $request->validate([
            'nickname' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'name' => 'required|string',
            'surnames' => 'required|string',
            'center' => 'required|string'
        ]);

        return Teacher::create([
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'center' => $data['center'],
            'name' => $data['name'],
            'surnames' => $data['surnames'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public static function updateFromRequest(Request $request): Teacher|null
    {
        $data = $request->validate([
            'id' => 'required|int|gt:0',
            'nickname' => 'required|string',
            'email' => 'required|email',
            'name' => 'required|string',
            'surnames' => 'required|string',
            'center' => 'required|string'
        ]);

        $teacher = Teacher::find($data['id']);

        if (!$teacher) {
            return null;
        }

        $oldTeacher = $teacher;

        $teacher->nickname = $data['nickname'];
        $teacher->email = $data['email'];
        $teacher->name = $data['name'];
        $teacher->surnames = $data['surnames'];
        $teacher->center = $data['center'];

        $teacher->save();

        return $oldTeacher;
    }
}
