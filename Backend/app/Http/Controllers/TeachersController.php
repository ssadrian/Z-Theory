<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeachersController extends Controller
{
    public function all(): \Illuminate\Database\Eloquent\Collection|\LaravelIdea\Helper\App\Models\_IH_Teacher_C|array
    {
        return Teacher::all();
    }

    public function get(Request $request): \Illuminate\Http\Response|Teacher|\LaravelIdea\Helper\App\Models\_IH_Teacher_C|array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
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

    public function create(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $teacher = Teacher::createFromRequest($request);
        $teacher->save();

        // Created
        return response(status: 201);
    }

    public function update(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
    {
        $teacher = Teacher::updateFromRequest($request);

        if (empty($teacher)) {
            // No Content
            return response(status: 204);
        }

        return response($teacher);
    }

    public function delete(Request $request): \Illuminate\Http\Response|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory
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
