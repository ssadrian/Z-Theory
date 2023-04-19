<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EvaluationHistoryController;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\Response;

class EvaluationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        $data = $request->validate([
            'evaluator' => 'required|exists:students,id',
            'subject' => 'required|different:evaluator|exists:students,id',
            'skill' => 'required|exists:skills,id',
            'kudos' => 'required|int|gt:0'
        ]);

        $evaluator = Student::find($data['evaluator']);
        $subject = Student::find($data['subject']);

        $availableKudos = $evaluator->kudos;

        if (
            empty($availableKudos)
            || $availableKudos < $data['kudos']
        ) {
            return response(
                status: Response::HTTP_BAD_REQUEST
            );
        }

        $subjectSkills = $subject->skills();
        $targetSkill = $subjectSkills->find($data['skill']);

        $evaluator->kudos -= $data['kudos'];
        $targetSkill->pivot->kudos += $data['kudos'];

        $evaluator->save();
        $targetSkill->pivot->save();

        Student::find($data['evaluator'])
            ->evaluationHistory()
            ->syncWithoutDetaching([
                $data['skill'] => [
                    'subject' => $data['subject'],
                    'kudos' => $data['kudos'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]);

        // Save a new history
        EvaluationHistoryController::store($data);

        return response(
            status: Response::HTTP_OK
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request): Response
    {
        $data = $request->validate([
            'evaluator' => 'required|exists:students,id',
            'subject' => 'required|different:evaluator|exists:students,id',
            'skill' => 'required|exists:skills,id'
        ]);

        $evaluator = Student::find($data['evaluator']);
        $subject = Student::find($data['subject']);

        $targetHistory = $evaluator
            ->evaluationHistory()->get()
            ->where('pivot.skill_id', $data['skill'])
            ->where('pivot.subject', $data['subject'])
            ->last();

        $subjectSkills = $subject->skills();
        $targetSkill = $subjectSkills->find($data['skill']);

        // Remove the given points, these points will be lost forever
        $targetSkill->pivot->kudos -= $targetHistory->pivot->kudos;

        $targetHistory->pivot->delete();
        $targetSkill->pivot->save();

        return response(
            status: Response::HTTP_OK
        );
    }
}
