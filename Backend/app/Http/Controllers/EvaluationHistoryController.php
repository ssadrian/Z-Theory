<?php

namespace App\Http\Controllers;

use App\Models\EvaluationHistory;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class EvaluationHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return response(
            EvaluationHistory::with(['evaluator', 'subject', 'ranking', 'skill'])->all()
            , Response::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    static public function store(array $data): Response
    {
        Validator::validate($data, [
            'evaluator' => 'required|exists:students,id',
            'subject' => 'required|different:evaluator|exists:students,id',
            'skill_id' => 'required|exists:skills,id',
            'ranking_id' => 'required|exists:rankings,id',
            'kudos' => 'required|gt:0'
        ]);

        EvaluationHistory::create([
            'evaluator' => $data['evaluator'],
            'subject' => $data['subject'],
            'skill_id' => $data['skill_id'],
            'ranking_id' => $data['ranking_id'],
            'kudos' => $data['kudos']
        ]);

        return response(
            status: Response::HTTP_OK
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        Validator::validate(['id' => $id], [
            'id' => 'required|exists:evaluation_history'
        ]);

        return response(
            EvaluationHistory::find($id)
            , Response::HTTP_OK
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): Response
    {
        Validator::validate(['id' => $id], [
            'id' => 'required|exists:evaluation_history'
        ]);

        EvaluationHistory::destroy($id);

        return response(
            status: Response::HTTP_OK
        );
    }

    public function forTeacher(int $teacherId)
    {
        Validator::validate(['id' => $teacherId], [
            'id' => 'required|exists:teachers',
        ]);

        $teacher = Teacher::find($teacherId);
        $rankings = $teacher->rankingsCreated()->with(['students'])->get();

        $students = [];

        foreach ($rankings as $ranking) {
            foreach ($ranking->students()->get() as $student) {
                $students[] = $student->id;
            }
        }

        $histories = EvaluationHistory::with(['evaluator', 'subject', 'ranking', 'skill'])
            ->whereIn('evaluator', $students)
            ->get();

        // TODO: Hide password field from the evaluator and subject relationships
        return response(
            $histories
            , Response::HTTP_OK
        );
    }
}
