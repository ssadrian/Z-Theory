<?php

namespace App\Http\Controllers;

use App\{Enums\JoinStatus, Models\Ranking};
use Illuminate\{Http\Request, Http\Response, Support\Carbon, Support\Facades\Validator, Validation\ValidationException};

class RankingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(
            Ranking::with(['students', 'queue', 'assignments', 'creator'])->get()
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
            'code' => 'required|uuid|unique:rankings',
            'name' => 'required|string|unique:rankings',
            'creator' => 'required|exists:teachers,id'
        ]);

        $ranking = Ranking::create($data);

        return response(
            Ranking::with(['creator'])
                ->find($ranking->id)
            , 201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param $code
     * @return Response
     * @throws ValidationException
     */
    public function show($code): Response
    {
        Validator::validate(['code' => $code], [
            'code' => 'required|exists:rankings'
        ]);

        return response(
            Ranking::with(['queue', 'students', 'assignments', 'creator'])
                ->firstWhere('code', $code)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $oldRankCode
     * @param Request $request
     * @return Response
     */
    public function update($oldRankCode, Request $request): Response
    {
        // Append ranking's id from url to request's body
        $request['target'] = $oldRankCode;

        $data = $request->validate([
            'target' => 'required|exists:rankings,code',
            'code' => 'sometimes|nullable|unique:rankings',
            'name' => 'sometimes|nullable|string',
            'creator' => 'sometimes|nullable|exists:teachers,id'
        ]);

        $previousRanking = Ranking::with(['creator', 'students', 'queue'])
            ->firstWhere('code', $oldRankCode);

        $ranking = Ranking::with(['creator', 'students', 'queue'])
            ->firstWhere('code', $oldRankCode);

        foreach ($data as $key => $value) {
            if (empty($value)) {
                $ranking->makeHidden($key);
            }
        }

        $ranking->fill($data);
        $success = $ranking->save();

        return response(
            $previousRanking,
            status: $success ? 200 : 400
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $code
     * @return Response
     * @throws ValidationException
     */
    public function destroy($code): Response
    {
        Validator::validate(['code' => $code], [
            'code' => 'required|exists:rankings'
        ]);

        Ranking::firstWhere('code', $code)->delete();
        return response(
            status: 200
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
            Ranking::with(['students', 'queue', 'assignments'])
                ->where('creator', $teacherId)
                ->get()
        );
    }

    /**
     * @param $studentId
     * @return Response
     * @throws ValidationException
     */
    public function forStudent($studentId): Response
    {
        Validator::validate(['id' => $studentId], [
            'id' => 'required|exists:students'
        ]);

        $leaderboards = [];
        $rankings = Ranking::with(['students', 'assignments'])->get();

        foreach ($rankings as $ranking) {
            if (!$ranking->students->contains($studentId)) {
                continue;
            }

            $ranking->students->makeHidden(['email', 'password', 'name', 'surnames']);
            foreach ($ranking->students as $student) {
                $student->pivot->makeHidden(['ranking_id', 'student_id']);
            }

            $leaderboards[] = $ranking;
        }

        return response(
            $leaderboards
        );
    }

    /**
     * @param $studentId
     * @param Request $request
     * @return Response
     */
    public function assignStudent($studentId, Request $request): Response
    {
        // Append student's id from url to request's body
        $request['student_id'] = $studentId;

        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'code' => 'required|exists:rankings'
        ]);

        $ranking = Ranking::with(['students', 'queue'])
            ->firstWhere('code', $data['code']);

        // Don't repeat same pending/accepted queue for student and ranking
        if (
            Ranking::with(['queue'])
                ->firstWhere('code', $data['code'])
                ->queue()
                ->where('student_id', $data['student_id'])
                ->count() !== 0
        ) {
            return response(
                status: 400
            );
        }

        $ranking->queue()->attach($studentId, [
            'join_status_id' => JoinStatus::Pending->value,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        return response(
            $ranking
        );
    }

    /**
     * @param $studentId
     * @param Request $request
     * @return Response
     */
    public function acceptStudent($studentId, Request $request): Response
    {
        // Append student's id from url to request's body
        $request['student_id'] = $studentId;

        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'code' => 'required|exists:rankings'
        ]);

        $ranking = Ranking::with(['queue', 'students'])
            ->firstWhere('code', $data['code']);

        // Don't repeat same pending/accepted queue for student and ranking
        if (
            Ranking::with(['queue'])
                ->firstWhere('code', $data['code'])
                ->queue()
                ->where('student_id', $data['student_id'])
                ->where('join_status_id', JoinStatus::Pending)
                ->count() === 0
        ) {
            return response(
                status: 400
            );
        }

        $queue = $ranking->queue()
            ->where('student_id', $data['student_id'])
            ->firstWhere('join_status_id', JoinStatus::Pending->value);

        $queue->pivot->join_status_id = JoinStatus::Accepted->value;
        $ranking->students()->attach($data['student_id'], [
            'points' => 0
        ]);

        // Update and save queue and refresh the ranking with the new values
        $queue->push();
        $ranking->refresh();

        return response(
            $ranking
        );
    }

    /**
     * @param $studentId
     * @param Request $request
     * @return Response
     */
    public function declineStudent($studentId, Request $request): Response
    {
        // Append student's id from url to request's body
        $request['student_id'] = $studentId;

        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'code' => 'required|exists:rankings'
        ]);

        $ranking = Ranking::with(['queue'])
            ->firstWhere('code', $data['code']);

        // Don't repeat same pending/accepted queue for student and ranking
        if (
            Ranking::with(['queue'])
                ->firstWhere('code', $data['code'])
                ->queue()
                ->where('student_id', $data['student_id'])
                ->where('join_status_id', JoinStatus::Pending)
                ->count() === 0
        ) {
            return response(
                status: 400
            );
        }

        $queue = $ranking->queue()
            ->where([
                'student_id' => $data['student_id'],
                'ranking_id' => $data['ranking_id']
            ])
            ->firstWhere('join_status_id', JoinStatus::Pending->value);
        $queue->pivot->join_status_id = JoinStatus::Declined->value;
        $queue->push();

        $ranking->refresh();
        return response(
            $ranking
        );
    }

    /**
     * @param $rankingCode
     * @param $studentId
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function updateForStudent($rankingCode, $studentId, Request $request): Response
    {
        // Append ranking's code and student's id from url to request's body
        Validator::validate([
            'code' => $rankingCode,
            'id' => $studentId
        ], [
            'code' => 'required|exists:rankings',
            'id' => 'required|exists:students|exists:ranking_student,student_id'
        ]);

        $data = $request->validate([
            'points' => 'sometimes|nullable|int'
        ]);

        $previousRanking = Ranking::with(['students'])
            ->firstWhere('code', $rankingCode)
            ->students()
            ->firstWhere('student_id', $studentId);

        $ranking = Ranking::with(['students'])
            ->firstWhere('code', $rankingCode)
            ->students()
            ->firstWhere('student_id', $studentId);

        $ranking->pivot->points = $data['points'];
        $success = $ranking->pivot->save();

        return response(
            $previousRanking,
            status: $success ? 200 : 400
        );
    }

    /**
     * @param $teacherId
     * @return Response
     * @throws ValidationException
     */
    public function queuesForTeacher($teacherId): Response
    {
        $data = Validator::validate([
            'id' => $teacherId
        ], [
            'id' => 'required|exists:teachers'
        ]);

        return response(
            Ranking::with(['queue'])
                ->where('creator', $data['id'])
                ->get()
        );
    }
}
