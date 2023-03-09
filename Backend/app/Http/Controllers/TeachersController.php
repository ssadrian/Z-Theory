<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|array
     */
    public function index(): Collection|array
    {
        return Teacher::with('rankings_created')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $teacher = Teacher::createFromRequest($request);
        $teacher->save();

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
            'id' => 'required|exists:teachers'
        ]);
        $this->throwIfInvalid($validator);

        return Teacher::with('rankings_created')
            ->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param Request $request
     * @return Teacher
     * @throws ValidationException
     */
    public function update($id, Request $request): Teacher
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:teachers'
        ]);
        $this->throwIfInvalid($validator);

        return Teacher::updateFromRequest($id, $request);
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
            'id' => 'required|exists:teachers'
        ]);
        $this->throwIfInvalid($validator);

        return response(
            status: Teacher::find($id)->delete() ? 200 : 204
        );
    }

    public function changePassword(Request $request): Response
    {
        $data = $request->validate([
            'id' => 'required|exists:teachers',
            'password' => 'required|string',
            'new_password' => 'required|string'
        ]);

        $teacher = Teacher::find($data['id']);

        if (!Hash::check($data['password'], $teacher->password)) {
            // Unprocessable Content
            return response(status: 422);
        }

        $teacher->password = Hash::make($data['new_password']);
        $teacher->save();

        // Ok
        return response(status: 200);
    }
}
