<?php

namespace App\Models;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Student extends Model
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
        'birth_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    public static function createFromRequest(Request $request)
    {
        $data = $request->validate([
            "nickname" => "required|string",
            "name" => "required|string",
            "surnames" => "required|string",
            "email" => "required|email",
            "password" => "required|confirmed",
            "birth_date" => "required|date"
        ]);

        return Student::create([
            "nickname" => $data["nickname"],
            "name" => $data["name"],
            "surnames" => $data["surnames"],
            "email" => $data["email"],
            "password" => Hash::make($data["password"]),
            "birth_date" => $data["birth_date"]
        ]);
    }

    public static function updateFromRequest(Request $request): Student|null
    {
        $data = $request->validate([
            "id" => "required|int",
            "nickname" => "required|string",
            "name" => "required|string",
            "surnames" => "required|string",
            "email" => "required|email",
            "birth_date" => "required|date"
        ]);

        $student = Student::find($data['id']);

        if (!$student) {
            return null;
        }

        $oldStudent = $student;

        $student->nickname = $data['nickname'];
        $student->email = $data['email'];
        $student->name = $data['name'];
        $student->surnames = $data['surnames'];
        $student->birth_date = $data['birth_date'];

        $student->save();

        return $oldStudent;
    }
}
