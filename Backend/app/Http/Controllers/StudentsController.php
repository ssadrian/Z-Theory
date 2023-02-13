<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use LaravelIdea\Helper\App\Models\_IH_Student_C;
use LaravelIdea\Helper\App\Models\_IH_Student_QB;

class StudentsController extends Controller
{
    public function all(): Collection|array
    {
        return Student::with("rankings")->get();
    }

    public function get(Request $request): Collection|array
    {
        $data = $request->validate([
            "id" => "required|exists:students",
        ]);

        return Student::find($data["id"])->with('rankings')->get();
    }

    public function create(Request $request): Response|Application|ResponseFactory
    {
        $student = Student::createFromRequest($request);
        $student->save();

        // Created
        return response(status: 201);
    }

    public function update(Request $request): Response|Application|ResponseFactory
    {
        $student = Student::updateFromRequest($request);

        if (empty($student)) {
            // No Content
            return response(status: 204);
        }

        return response($student);
    }

    public function delete(Request $request): Response|Application|ResponseFactory
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
