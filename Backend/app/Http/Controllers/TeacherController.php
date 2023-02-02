<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function all()
    {
        return Teacher::all();
    }

    public function get(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|int|gt:0',
        ]);

        $teacher = Teacher::find($data['id']);

        if (!$teacher) {
            // No Content
            return response(status: 204);
        }

        return $teacher;
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'nickname' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'name' => 'required|string',
            'surnames' => 'required|string',
            'center' => 'required|string'
        ]);

        $teacher = Teacher::create([
            'nickname' => $data['nickname'],
            'email' => $data['email'],
            'center' => $data['center'],
            'name' => $data['name'],
            'surnames' => $data['surnames'],
            'password' => $data['password']
        ]);

        $teacher->encryptPassword();
        $teacher->save();

        // Created
        return response(status: 201);
    }

    public function update(Request $request)
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
            // No Content
            return response(status: 204);
        }

        $oldTeacher = $teacher;

        $teacher->nickname = $data['nickname'];
        $teacher->email = $data['email'];
        $teacher->name = $data['name'];
        $teacher->surnames = $data['surnames'];
        $teacher->center = $data['center'];

        $teacher->save();

        return response($oldTeacher);
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|int|gt:0'
        ]);

        $teacher = Teacher::find($data['id']);

        if (!$teacher) {
            // No Content
            return response(status: 204);
        }

        return response(
            status: $teacher->delete() ? 200 : 204
        );
    }
}
