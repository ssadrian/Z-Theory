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

        $student = Student::all()
            ->firstWhere("email", $data["email"]);

        $token = $request->user()->createToken('token');

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
        $newStudent = Student::createFromRequest($request);

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
        $newTeacher = Teacher::createFromRequest($request);

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
