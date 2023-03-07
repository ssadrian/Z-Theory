<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

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
     * @return Response|Application|ResponseFactory
     */
    public function store(Request $request): Response|Application|ResponseFactory
    {
        $teacher = Teacher::createFromRequest($request);
        $teacher->save();

        // Created
        return response(status: 201);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response|Application|ResponseFactory
     */
    public function show($id): Model|Response|Builder|Application|ResponseFactory
    {
        $teacher = Teacher::with('rankings_created')->find($id);

        if (!$teacher) {
            // No Content
            return response(status: 204);
        }

        return $teacher;
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
        $teacher = Teacher::updateFromRequest($id, $request);

        if (empty($teacher)) {
            // No Content
            return response(status: 204);
        }

        return response($teacher);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id): Response
    {
        $teacher = Teacher::find($id);

        if (!$teacher) {
            // No Content
            return response(status: 204);
        }

        return response(
            status: $teacher->delete() ? 200 : 204
        );
    }

    public function changePassword(Request $request): Response|Application|ResponseFactory
    {
        $data = $request->validate([
            'id' => 'required|exists:teachers,id',
            'password' => 'required|string',
            'new_password' => 'required|string'
        ]);

        $teacher = Teacher::find($data['id']);

        if (!Hash::check($data['password'], $teacher->password)) {
            //  Unprocessable Content
            return response(status: 422);
        }

        $teacher->password = Hash::make($data['new_password']);
        $teacher->save();

        // Ok
        return response(status: 200);
    }
}
