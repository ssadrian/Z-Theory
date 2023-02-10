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
    public function login(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $studentToken = $this->loginStudent($request, $data);
        if ($studentToken) {
            return response()->json([
                'token' => $studentToken->plainTextToken
            ]);
        }

        $teacherToken = $this->loginTeacher($request, $data);
        if ($teacherToken) {
            return response()->json([
                'token' => $teacherToken->plainTextToken
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ]);
    }

    public function loginStudent(Request $request, array $data): NewAccessToken|bool
    {
        $student = Student::all()
            ->firstWhere("email", $data["email"]);

        if ($student && Hash::check($data["password"], $student->password)) {
            return $student->createToken('token');
        }

        return false;
    }

    public function loginTeacher(Request $request, array $data): NewAccessToken|bool
    {
        $teacher = Teacher::all()
            ->firstWhere('email', $data['email']);

        if ($teacher && Hash::check($data['password'], $teacher->password)) {
            return $teacher->createToken('token');
        }

        return false;
    }
}
