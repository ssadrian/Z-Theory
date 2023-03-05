<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class StudentsController extends Controller
{
    public function all(): Collection|array
    {
        return Student::with("rankings")->get();
    }

    public function get($id): Model|Response|Builder|Application|ResponseFactory
    {
        $student = Student::with('rankings')->firstWhere('id', $id);

        if (!$student) {
            // No Content
            return response(status: 204);
        }

        return $student;
    }

    public function create(Request $request): Response|Application|ResponseFactory
    {
        $student = Student::createFromRequest($request);
        $student->save();

        // Created
        return response(status: 201);
    }

    public function changePassword(Request $request): Response|Application|ResponseFactory
    {
        $data = $request->validate([
            'id' => 'required|int|gt:0',
            'password' => 'required|string',
            'new_password' => 'required|string'
        ]);

        $student = Student::find($data['id']);

        if (!($student || Hash::check($data['password'], $student->password))) {
            // Unprocessable Content
            return response(status: 422);
        }

        $student->password = Hash::make($data['new_password']);
        $student->save();

        // Ok
        return response(status: 200);
    }

    public function update($id, Request $request): Response|Application|ResponseFactory
    {
        $student = Student::updateFromRequest($id, $request);

        if (empty($student)) {
            // No Content
            return response(status: 204);
        }

        return response($student);
    }

    public function delete($id): Response|Application|ResponseFactory
    {
        $student = Student::find($id);

        if (!$student) {
            // No Content
            return response(status: 204);
        }

        return response(
            status: $student->delete() ? 200 : 204
        );
    }
}
