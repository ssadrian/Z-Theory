<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $token = $request->user()->createToken("token");

        $student = Student::all()
            ->firstWhere("email", $data["email"]);

        if ($student && Hash::check($data["password"], $student->password)) {
            return $token;
        }

        $teacher = Teacher::all()
            ->firstWhere("email", $data["email"]);

        if ($teacher && Hash::check($data["password"], $teacher->password)) {
            return $token;
        }

        return response()->json([
            "msg" => "Invalid email or password"
        ]);
    }

    public function registerStudent(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string",
            "surnames" => "required|string",
            "nickname" => "required|string",
            "email" => "required|email",
            "password" => "required|confirmed",
            "birth_date" => "required|date"
        ]);

        $newStudent = Student::create([
            "name" => $data["name"],
            "surnames" => $data["surnames"],
            "nickname" => $data["nickname"],
            "email" => $data["email"],
            "password" => $data["password"],
            "birth_date" => $data["birth_date"]
        ]);

        if (empty($newStudent)) {
            return response()->json([
                "msg" => "Could not register student"
            ]);
        }

        $newStudent->save();

        return response()->json([
            "msg" => "Student Registered"
        ], 201);
    }

    public function registerTeacher(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string",
            "surnames" => "required|string",
            "nickname" => "required|string",
            "email" => "required|email",
            "password" => "required|confirmed",
            "center" => "required|string"
        ]);

        $newTeacher = Teacher::create([
            "name" => $data["name"],
            "surnames" => $data["surnames"],
            "nickname" => $data["nickname"],
            "email" => $data["email"],
            "password" => $data["password"],
            "center" => $data["center"]
        ]);

        if (empty($newTeacher)) {
            return response()->json([
                "msg" => "Could not register teacher"
            ]);
        }

        $newTeacher->save();

        return response()->json([
            "msg" => "Teacher Registered"
        ], 201);
    }
}
