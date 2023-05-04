<?php

namespace App\Http\Controllers;

use App\Http\Controllers\EvaluationHistoryController;
use App\Models\EvaluationHistory;
use App\Models\Ranking;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Str;

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
    public function store(Request $request)
    {
        $user = $request->user();
        $tokenId = explode('|', $request->bearerToken())[0];
        $token = $user->tokens()->find($tokenId);

        if (!$token->can('store:evaluation')) {
            return $this->forbidden();
        }

        $data = $request->validate([
            'evaluator' => 'required|exists:students,id',
            'subject' => 'required|different:evaluator|exists:students,id',
            'skill_id' => 'required|exists:skills,id',
            'ranking_id' => 'required|exists:rankings,id',
            'kudos' => 'required|gt:0'
        ]);

        $rankingStudents = Ranking::find($data['ranking_id'])->students()->get();
        if (!($rankingStudents->contains($data['evaluator']) || $rankingStudents->contains($data['subject']))) {
            return response(
                status: Response::HTTP_BAD_REQUEST
            );
        }

        $evaluator = Student::find($data['evaluator']);
        $subject = Student::find($data['subject']);

        $evaluatorRanking = $evaluator->rankings()->find($data['ranking_id']);
        $availableKudos = $evaluatorRanking->pivot->kudos;

        if (
            empty($availableKudos)
            || $availableKudos < $data['kudos']
        ) {
            return response(
                status: Response::HTTP_BAD_REQUEST
            );
        }

        $targetSkill = $subject->skills()->find($data['skill_id']);

        $evaluatorRanking->pivot->kudos -= $data['kudos'];
        $targetSkill->pivot->kudos += $data['kudos'];

        $evaluatorRanking->pivot->save();
        $targetSkill->pivot->save();

        EvaluationController::updateSkillImage($data['subject'], $data['skill_id'], $data['ranking_id']);

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
    public function destroy($evaluationId, Request $request)
    {
        $tokenId = explode('|', $request->bearerToken())[0];
        $token = $request->user()->tokens()->find($tokenId);

        if (!$token->can('destroy:evaluation')) {
            return $this->forbidden();
        }

        Validator::validate(['evaluation_id' => $evaluationId], [
            'evaluation_id' => 'required|exists:evaluation_history,id'
        ]);

        $evaluationHistory = EvaluationHistory::find($evaluationId);

        $evaluator = Student::find($evaluationHistory->evaluator);
        $subject = Student::find($evaluationHistory->subject);

        $targetSkill = $subject->skills()->find($evaluationHistory->skill_id);

        // Remove the given points, these points will be lost forever
        $targetSkill->pivot->kudos -= $evaluationHistory->kudos;

        $targetSkill->pivot->save();

        EvaluationHistory::destroy($evaluationId);
        EvaluationController::updateSkillImage($evaluationHistory->subject, $evaluationHistory->skill_id, $evaluationHistory->ranking_id);

        return response(
            status: Response::HTTP_OK
        );
    }

    public static function updateSkillImage($studentId, $skillId, $rankingId)
    {
        Validator::validate([
            'student_id' => $studentId,
            'skill_id' => $skillId,
            'ranking_id' => $rankingId
        ], [
            'student_id' => 'required|exists:students,id',
            'skill_id' => 'required|exists:skills,id',
            'ranking_id' => 'required|exists:rankings,id'
        ]);

        $apiUrl = env('APP_URL');

        $target = Student::find($studentId)
            ->skills()
            ->find($skillId);

        $kudosReceived = $target->pivot->kudos;

        /**
         * The branches get executed resulting in the following form
         * false, true, true, ...
         *
         * Default executes when the target has under 1k kudos which results in no medal
         */
        $level = match (true) {
            ($kudosReceived >= 10_000) => 5,
            ($kudosReceived >= 7_000) => 4,
            ($kudosReceived >= 4_000) => 3,
            ($kudosReceived >= 2_000) => 2,
            ($kudosReceived >= 1_000) => 1,
            default => null
        };

        $target->name = Str::lower($target->name);
        if (empty($level)) {
            $target->pivot->image = null;
        } else {
            $target->pivot->image = "{$apiUrl}/medals/{$target->name}/{$level}.png";
        }

        $target->pivot->save();
    }
}
