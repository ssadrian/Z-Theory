<?php

namespace App\Http\Controllers;

use App\{Models\Student, Models\Teacher};
use Illuminate\{Http\JsonResponse, Http\Request, Support\Facades\Hash};
use Illuminate\Http\Response;
use Illuminate\Queue\Failed\PrunableFailedJobProvider;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginStudent(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $student = Student::with('rankings')
            ->firstWhere('email', $data['email']);

        if ($student && Hash::check($data['password'], $student->password)) {
            return response()->json([
                'user' => $student,
                'role' => 'student',
                'token' => $student->createToken('token', $student->abilities)->plainTextToken
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
                'token' => $teacher->createToken('token', $teacher->abilities)->plainTextToken
            ]);
        }

        return response()->json([
            'message' => 'Invalid credentials'
        ]);
    }

    public function logout(Request $request): Response
    {
        $request->user()->tokens()->delete();

        return response(
            status: Response::HTTP_OK
        );
    }
}
