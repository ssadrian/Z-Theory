<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\{
    Http\Request,
    Http\Response,
    Support\Facades\Hash,
    Support\Facades\Validator,
    Validation\ValidationException
};

class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(
            Teacher::with('rankingsCreated')->get()
        );
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
            'nickname' => 'required|unique:students|unique:teachers',
            'email' => 'required|email|unique:students|unique:teachers',
            'password' => 'required|confirmed',
            'name' => 'required|string',
            'surnames' => 'required|string',
            'center' => 'required|string',
            'avatar' => 'sometimes|string'
        ]);

        return response(
            Teacher::create($data)
            , 201
        );
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
        Validator::validate(['id' => $id], [
            'id' => 'required|exists:teachers'
        ]);

        return response(
            Teacher::with('rankings_created')
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
        // Append teacher's id from url to request's body
        $request['id'] = $id;

        $data = $request->validate([
            'id' => 'required|exists:students',
            'nickname' => 'sometimes|nullable|string',
            'name' => 'sometimes|nullable|string',
            'surnames' => 'sometimes|nullable|string',
            'avatar' => 'sometimes|nullable|string',
            'center' => 'required|string',
        ]);

        $previousTeacher = Teacher::with(['rankingsCreated'])
            ->find($id);

        $teacher = Teacher::with(['rankingsCreated'])
            ->find($id);

        foreach ($data as $key => $value) {
            if (empty($value)) {
                $teacher->makeHidden($key);
            }
        }

        $teacher->fill($data);
        $success = $teacher->save();

        return response(
            $previousTeacher,
            status: $success ? 200 : 400
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
        Validator::validate(['id' => $id], [
            'id' => 'required|exists:teachers'
        ]);

        return response(
            status: Teacher::destroy($id) ? 200 : 204
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request): Response
    {
        $data = $request->validate([
            'id' => 'required|exists:teachers',
            'password' => 'required|string',
            'new_password' => 'required|string'
        ]);

        $teacher = Teacher::find($data['id']);

        if (!Hash::check($data['password'], $teacher->password)) {
            // Bad Request
            return response(
                status: 400
            );
        }

        $teacher->password = Hash::make($data['new_password']);
        $teacher->save();

        // Ok
        return response(
            status: 200
        );
    }
}
