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

        $studentLogin = $this->loginStudent($request, $data);
        if ($studentLogin) {
            return response()->json([
                'token' => $studentLogin
            ]);
        }

        $teacherLogin = $this->loginTeacher($request, $data);
        if ($teacherLogin) {
            return response()->json([
                'token' => $teacherLogin
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
            return $request->user()->createToken('token');
        }

        return false;
    }

    public function loginTeacher(Request $request, array $data): NewAccessToken|bool
    {
        $teacher = Teacher::all()
            ->firstWhere('email', $data['email']);

        if ($teacher && Hash::check($data['password'], $teacher->password)) {
            return $request->user()->createToken('token');
        }

        return false;
    }
}
