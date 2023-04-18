<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\Student;
use Illuminate\{Http\Request,
    Http\Response,
    Support\Facades\Hash,
    Support\Facades\Validator,
    Validation\ValidationException
};

class StudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(
            Student::with(['rankings', 'assignments', 'skills'])->get()
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
            'nickname' => 'required|string|unique:students|unique:teachers',
            'email' => 'required|email|unique:students|unique:teachers',
            'name' => 'required|string',
            'surnames' => 'required|string',
            'password' => 'required|confirmed',
            'birth_date' => 'required|date',
            'avatar' => 'sometimes|nullable|string'
        ]);

        // Default value for kudos required field
        $data['kudos'] = 1_000;
        $data['password'] = Hash::make($data['password']);

        $student = Student::create($data);
        $student = Student::with(['skills'])->find($student->id);

        $allSkills = Skill::all('id');
        foreach ($allSkills as $skill) {
            $student->skills()->syncWithoutDetaching([
                $skill->id => ['kudos' => 0]
            ]);
        }

        $student->refresh();
        return response(
            $student
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
            'id' => 'required|exists:students'
        ]);

        return response(
            Student::with(['rankings', 'assignments', 'skills'])
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
            'kudos' => 'sometimes|nullable|int|gte:0',
        ]);

        $previousStudent = Student::with(['rankings'])
            ->find($id);

        $student = Student::with(['rankings'])
            ->find($id);

        foreach ($data as $key => $value) {
            if (empty($value)) {
                $student->makeHidden($key);
                unset($data[$key]);
            }
        }

        $student->fill($data);
        $success = $student->save();

        return response(
            $previousStudent,
            status: $success ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
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
            'id' => 'required|exists:students'
        ]);

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
            // Bad Request
            return response(
                status: Response::HTTP_BAD_REQUEST
            );
        }

        $student->password = Hash::make($data['new_password']);
        $student->save();

        // Ok
        return response(
            status: Response::HTTP_OK
        );
    }
}
