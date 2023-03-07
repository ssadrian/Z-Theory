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
    /**
     * Display a listing of the resource.
     *
     * @return Collection|array
     */
    public function index(): Collection|array
    {
        return Student::with("rankings")->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response|Application|ResponseFactory
     */
    public function store(Request $request): Response|Application|ResponseFactory
    {
        $student = Student::createFromRequest($request);
        $student->save();

        // Created
        return response(status: 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Model|Response|Builder|Application|ResponseFactory
     */
    public function show($id): Model|Response|Builder|Application|ResponseFactory
    {
        $student = Student::with('rankings')->firstWhere('id', $id);

        if (!$student) {
            // No Content
            return response(status: 204);
        }

        return $student;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param Request $request
     * @return Response|Application|ResponseFactory
     */
    public function update($id, Request $request): Response|Application|ResponseFactory
    {
        $student = Student::updateFromRequest($id, $request);

        if (empty($student)) {
            // No Content
            return response(status: 204);
        }

        return response($student);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response|Application|ResponseFactory
     */
    public function destroy($id): Response|Application|ResponseFactory
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

    /**
     * @param Request $request
     * @return Response|Application|ResponseFactory
     */
    public function changePassword(Request $request): Response|Application|ResponseFactory
    {
        $data = $request->validate([
            'id' => 'required|exists:students,id',
            'password' => 'required|string',
            'new_password' => 'required|string'
        ]);

        $student = Student::find($data['id']);

        if (!Hash::check($data['password'], $student->password)) {
            // Unprocessable Content
            return response(status: 422);
        }

        $student->password = Hash::make($data['new_password']);
        $student->save();

        // Ok
        return response(status: 200);
    }
}
