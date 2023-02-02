<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function all()
    {
        return Student::all();
    }

    public function get(Request $request)
    {
        $data = $request->validate([
            "id" => "required|int|gt:0",
        ]);

        $student = Student::find($data["id"]);

        if (!$student) {
            // No Content
            return response(status: 204);
        }

        return $student;
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            "nickname" => "required|string",
            "email" => "required|email",
            "password" => "required|confirmed",
            "name" => "required|string",
            "surnames" => "required|string",
            "birth_date" => "required|date"
        ]);

        $student = Student::create([
            "nickname" => $data["nickname"],
            "email" => $data["email"],
            "birth_date" => $data["birth_date"],
            "name" => $data["name"],
            "surnames" => $data["surnames"],
            "password" => $data["password"]
        ]);

        $student->encryptPassword();
        $student->save();

        // Created
        return response(status: 201);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            "id" => "required|int|gt:0",
            "nickname" => "required|string",
            "email" => "required|email",
            "name" => "required|string",
            "surnames" => "required|string",
            "birth_date" => "required|date"
        ]);

        $student = Student::find($data["id"]);

        if (!$student) {
            // No Content
            return response(status: 204);
        }

        $oldStudent = $student;

        $student->nickname = $data["nickname"];
        $student->email = $data["email"];
        $student->name = $data["name"];
        $student->surnames = $data["surnames"];
        $student->birth_date = $data["birth_date"];

        $student->save();

        return response($oldStudent);
    }

    public function delete(Request $request)
    {
        $data = $request->validate([
            "id" => "required|int|gt:0"
        ]);

        $student = Student::find($data["id"]);

        if (!$student) {
            // No Content
            return response(status: 204);
        }

        return response(
            status: $student->delete() ? 200 : 204
        );
    }
}
