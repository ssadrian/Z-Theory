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
            'title' => 'required|string|unique:assignments',
            'points' => 'required|int',
            'description' => 'sometimes|nullable|string',
            'content' => 'sometimes|nullable|string',
        ]);

        // Model requires the teacher's id, however the user must see it as creator
        $data['teacher_id'] = $data['creator'];

        $assignment = Assignment::create($data);
        return response(
            Assignment::with(['creator'])->find($assignment->id)
            , Response::HTTP_CREATED
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
            'id' => 'required|exists:assignments'
        ]);

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
            'title' => 'sometimes|nullable|string|unique:assignments',
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
            status: $success ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
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
            status: Assignment::destroy($id) ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }

    /**
     * @param $rankCode
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function assignToRanking($rankCode, Request $request): Response
    {
        // Append rank's code from url to request's body
        Validator::validate(['code' => $rankCode], [
            'code' => 'required|exists:rankings'
        ]);

        $data = $request->validate([
            'id' => 'required|exists:assignments'
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
                        'status' => 'Pending'
                    ],
                ]);
        }

        $assignments = Assignment::with(['creator', 'rankingsAssigned'])
            ->find($data['id']);
        $assignments->rankingsAssigned->makeHidden('pivot');

        return response(
            $assignments
        );
    }

    /**
     * @param $id
     * @param $rankCode
     * @return Response
     * @throws ValidationException
     */
    public function removeFromRanking($id, $rankCode): Response
    {
        Validator::validate([
            'id' => $id,
            'code' => $rankCode
        ], [
            'id' => 'required|exists:assignments',
            'code' => 'required|exists:rankings'
        ]);

        Assignment::find($id)
            ->rankingsAssigned()
            ->detach(Ranking::all()->firstWhere('code', $rankCode));

        $ranking = Ranking::with('students')
            ->firstWhere('code', $rankCode);

        // Each member of the ranking with the assignment should have their assignments
        foreach ($ranking->students as $student) {
            Student::find($student->id)
                ->assignments()
                ->detach($id);
        }

        return response(
            Response::HTTP_OK
        );
    }

    /**
     * @param $teacherId
     * @return Response
     * @throws ValidationException
     */
    public function createdBy($teacherId): Response
    {
        Validator::validate(['id' => $teacherId], [
            'id' => 'required|exists:teachers'
        ]);

        return response(
            Assignment::where('teacher_id', $teacherId)->get()
        );
    }

    /**
     * @param $assignmentId
     * @param $studentId
     * @param Request $request
     * @return Response
     */
    public function updateForStudent($assignmentId, $studentId, Request $request): Response
    {
        // Append ranking's code and student's id from url to request's body
        $data['student_id'] = $studentId;
        $data['assignment_id'] = $assignmentId;

        $data = $request->validate([
            'student_id' => 'required|exists:students,id|exists:assignment_student',
            'assignment_id' => 'required|exists:assignments,id|exists:assignment_student',
            'mark' => 'sometimes|nullable|int',
            'file' => 'sometimes|nullable|string',
            'status' => 'sometimes|nullable|exists:assignment_statuses'
        ]);

        $previousTask = Assignment::with(['creator', 'studentsAssigned'])
            ->find($assignmentId)
            ->studentsAssigned()
            ->firstWhere('student_id', $studentId);

        $task = Assignment::with(['students'])
            ->find($assignmentId)
            ->studentsAssigned()
            ->firstWhere('student_id', $studentId);

        $task->pivot->mark = $data['mark'];
        $task->pivot->file = $data['file'];
        $task->pivot->status = $data['status'];
        $success = $task->pivot->save();

        return response(
            $previousTask,
            status: $success ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST
        );
    }
}
