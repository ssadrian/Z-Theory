<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Student::with(['rankings'])->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $data = $request->validate([
            'nickname' => 'required|string|unique:students|unique:teachers',
            'email' => 'required|email|unique:students|unique:teachers',
            'name' => 'required|string',
            'surnames' => 'required|string',
            'password' => 'required|confirmed',
            'birth_date' => 'required|date',
            'avatar' => 'sometimes|nullable|string'
        ]);

        return response(Student::create($data), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     * @throws ValidationException
     */
    public function show($id): Response
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:students'
        ]);
        $this->throwIfInvalid($validator);

        return response(
            Student::with(['rankings'])
                ->find($id)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function update($id, Request $request): Response
    {
        // Append student's id from url to request's body
        $request['id'] = $id;

        $data = $request->validate([
            'id' => 'required|exists:students',
            'nickname' => 'sometimes|nullable|string',
            'name' => 'sometimes|nullable|string',
            'surnames' => 'sometimes|nullable|string',
            'birth_date' => 'sometimes|nullable|date',
            'avatar' => 'sometimes|nullable|string',
        ]);

        $previousStudent = Student::with(['rankings'])
            ->find($id);

        $student = Student::with(['rankings'])
            ->find($id);

        foreach ($data as $key => $value) {
            if (empty($value)) {
                $student->makeHidden($key);
            }
        }

        $student->fill($data);
        $success = $student->save();

        return response(
            $previousStudent,
            status: $success ? 200 : 422
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     * @throws ValidationException
     */
    public function destroy($id): Response
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:students'
        ]);
        $this->throwIfInvalid($validator);

        return response(
            status: Student::destroy($id) ? 200 : 204
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request): Response
    {
        $data = $request->validate([
            'id' => 'required|exists:students',
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
