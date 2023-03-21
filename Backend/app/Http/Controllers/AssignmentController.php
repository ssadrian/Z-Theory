<?php

namespace App\Http\Controllers;

use App\{Models\Assignment, Models\Ranking, Models\Student};
use Illuminate\{Http\Request, Http\Response, Support\Facades\Validator, Validation\ValidationException};

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(
            Assignment::with(['creator', 'rankingsAssigned', 'studentsAssigned'])->get()
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
            'creator' => 'required|exists:teachers,id',
            'title' => 'required|string',
            'points' => 'required|int',
            'description' => 'sometimes|nullable|string',
            'content' => 'sometimes|nullable|string',
        ]);

        // Model requires the teacher's id, however the user must see it as creator
        $data['teacher_id'] = $data['creator'];

        $assignment = Assignment::create($data);
        return response(
            Assignment::with(['creator'])->find($assignment->id)
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
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:assignments'
        ]);
        $this->throwIfInvalid($validator);

        return response(
            Assignment::with(['creator'])
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
        // Append assignment's id from url to request's body
        $request['id'] = $id;

        $data = $request->validate([
            'id' => 'required|exists:assignments',
            'title' => 'sometimes|nullable|string',
            'description' => 'sometimes|nullable|string',
            'content' => 'sometimes|nullable|string',
            'points' => 'sometimes|nullable|int',
            'creator' => 'sometimes|nullable|int'
        ]);

        $previousAssignment = Assignment::with(['creator'])->find($id);
        $assignment = Assignment::with(['creator'])->find($id);

        foreach ($data as $key => $value) {
            if (empty($value)) {
                $assignment->makeHidden($key);
            }
        }

        $assignment->fill($data);
        $success = $assignment->save();

        return response(
            $previousAssignment,
            status: $success ? 200 : 400
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id): Response
    {
        return response(
            status: Assignment::destroy($id) ? 200 : 400
        );
    }

    public function assignToRanking($rankCode, Request $request): Response
    {
        // Append rank's code from url to request's body
        $validator = Validator::make(['code' => $rankCode], [
            'code' => 'required|exists:rankings'
        ]);
        $this->throwIfInvalid($validator);

        $data = $request->validate([
            'id' => 'required|exists:assignments',
            'file' => 'required|string'
        ]);

        Assignment::find($data['id'])
            ->rankingsAssigned()
            ->syncWithoutDetaching(Ranking::all()->firstWhere('code', $rankCode));

        $ranking = Ranking::with('students')
            ->firstWhere('code', $rankCode);

        // Each member of the ranking with the assignment should have their assignments
        foreach ($ranking->students as $student) {
            Student::find($student->id)
                ->assignments()
                ->syncWithoutDetaching([
                    $data['id'] => [
                        'status' => 'Pending',
                        'file' => $data['file']
                    ],
                ]);
        }

        $assignment = Assignment::with(['creator', 'rankingsAssigned'])
            ->find($data['id']);
        $assignment->rankingsAssigned->makeHidden('pivot');

        return response(
            $assignment
        );
    }

    public function removeFromRanking($rankCode, Request $request): Response
    {
        // Append rank's code from url to request's body
        $validator = Validator::make(['code' => $rankCode], [
            'code' => 'required|exists:rankings'
        ]);
        $this->throwIfInvalid($validator);

        $data = $request->validate([
            'id' => 'required|exists:assignments',
        ]);

        Assignment::find($data['id'])
            ->rankingsAssigned()
            ->detach(Ranking::all()->firstWhere('code', $rankCode));

        $ranking = Ranking::with('students')
            ->firstWhere('code', $rankCode);

        // Each member of the ranking with the assignment should have their assignments
        foreach ($ranking->students as $student) {
            Student::find($student->id)
                ->assignments()
                ->detach($data['id']);
        }

        return response(
            status: 200
        );
    }
}
