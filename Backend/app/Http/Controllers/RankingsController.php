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
            Ranking::with(['students', 'queues', 'assignments'])->get()
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
        $validator = Validator::make(['code' => $code], [
            'code' => 'required|exists:rankings'
        ]);
        $this->throwIfInvalid($validator);

        return response(
            Ranking::with(['queues', students', 'assignments'])
                ->firstWhere('code', $code)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $code
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function update($oldRankCode, Request $request): Response
    {
        // Append ranking's id from url to request's body
        $validator = Validator::make(['code' => $oldRankCode], [
            'code' => 'required|exists:rankings'
        ]);
        $this->throwIfInvalid($validator);

        $data = $request->validate([
            'code' => 'sometimes|nullable|unique:rankings',
            'name' => 'sometimes|nullable|string',
            'creator' => 'sometimes|nullable|exists:teachers,id'
        ]);

        $previousRanking = Ranking::with(['creator', 'students', 'queues'])
            ->firstWhere('code', $oldRankCode);

        $ranking = Ranking::with(['creator', 'students', 'queues'])
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
     */
    public function destroy($code): Response
    {
        return response(
            status: Ranking::all()
                ->firstWhere('code', $code)
                ->delete() ? 200 : 204
        );
    }

    /**
     * @param $teacherId
     * @return Response
     * @throws ValidationException
     */
    public function createdBy($teacherId): Response
    {
        $validator = Validator::make(['id' => $teacherId], [
            'id' => 'required|exists:teachers'
        ]);
        $this->throwIfInvalid($validator);

        return response(
            Ranking::with(['students', 'queues', 'assignments'])
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
        $validator = Validator::make(['id' => $studentId], [
            'id' => 'required|exists:students'
        ]);
        $this->throwIfInvalid($validator);

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
     * @var array
     */
    private array $data = [];

    /**
     * @param $studentId
     * @param Request $request
     * @return Response
     */
    public function assignStudent($studentId, Request $request): Response
    {
        // Append student's id from url to request's body
        $request['student_id'] = $studentId;

        $this->data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'code' => 'required|exists:rankings'
        ]);

        $ranking = Ranking::with(['students', 'queues'])
            ->firstWhere('code', $this->data['code']);

        // Needed for the queues relationship
        $this->data['ranking_id'] = $ranking->id;

        // Don't repeat same pending/accepted queue for student and ranking
        if (
            Ranking::with([
                'queues' => function ($query) {
                    return $query
                        ->where('student_id', $this->data['student_id'])
                        ->where('ranking_id', $this->data['ranking_id'])
                        ->where('join_status_id', '<=', JoinStatus::Pending->value);
                }
            ])
                ->firstWhere('code', $this->data['code'])
                ->queues
                ->count() !== 0
        ) {
            return response(
                status: 400
            );
        }

        $ranking->queues()->attach($studentId, [
            'join_status_id' => JoinStatus::Pending->value,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $ranking->students()->attach($studentId, [
            'points' => 0,
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

        $this->data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'code' => 'required|exists:rankings'
        ]);

        $ranking = Ranking::with(['queues'])
            ->firstWhere('code', $this->data['code']);

        // Needed for the queues relationship
        $this->data['ranking_id'] = $ranking->id;

        // Don't repeat same pending/accepted queue for student and ranking
        if (
            Ranking::with([
                'queues' => function ($query) {
                    return $query
                        ->where('student_id', $this->data['student_id'])
                        ->where('ranking_id', $this->data['ranking_id'])
                        ->where('join_status_id', '<=', JoinStatus::Pending->value);
                }
            ])
                ->firstWhere('code', $this->data['code'])
                ->queues
                ->count() === 0
        ) {
            return response(
                status: 400
            );
        }

        $queue = $ranking->queues()
            ->where('student_id', 1)
            ->where('ranking_id', 1)
            ->firstWhere('join_status_id', JoinStatus::Pending->value);
        $queue->pivot->join_status_id = JoinStatus::Accepted->value;
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
        $validator = Validator::make([
            'code' => $rankingCode,
            'id' => $studentId
        ], [
            'code' => 'required|exists:rankings',
            'id' => 'required|exists:students|exists:ranking_student,student_id'
        ]);
        $this->throwIfInvalid($validator);

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
}
