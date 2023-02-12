<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Laravel\Sanctum\NewAccessToken;

class AuthController extends Controller
{
    public function loginStudent(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $student = Student::all()
            ->firstWhere("email", $data["email"]);

        if ($student && Hash::check($data["password"], $student->password)) {
            return response()->json([
                'user' => $student,
                'role' => 'student',
                'token' => $student->createToken('token')->plainTextToken
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ]);
    }

    public function loginTeacher(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $teacher = Teacher::all()
            ->firstWhere('email', $data['email']);

        if ($teacher && Hash::check($data['password'], $teacher->password)) {
            return response()->json([
                'user' => $teacher,
                'role' => 'teacher',
                'token' => $teacher->createToken('token')->plainTextToken
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ]);
    }
}
