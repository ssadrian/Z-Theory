<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
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
     * @return Response
     */
    public function store(Request $request): Response
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
     * @return Model|Response
     * @throws ValidationException
     */
    public function show($id): Model|Response
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:students'
        ]);
        $this->throwIfInvalid($validator);

        return Student::with('rankings')
            ->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param Request $request
     * @return array
     * @throws ValidationException
     */
    public function update($id, Request $request): array
    {
        $validator = Validator::make(['id' => $id], [
           'id' => 'required|exists:students'
        ]);
        $this->throwIfInvalid($validator);

        return Student::updateFromRequest($id, $request);
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
            status: Student::find($id)->delete() ? 200 : 204
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
