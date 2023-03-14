<?php

namespace App\Http\Controllers;

use App\Enums\JoinStatus;
use App\Models\Ranking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RankingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(Ranking::with(['students', 'queues'])->get());
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
            Ranking::with('students')
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
    public function update($code, Request $request): Response
    {
        // Append ranking's id from url to request's body
        $validator = Validator::make(['code' => $code], [
            'code' => 'required|exists:rankings'
        ]);
        $this->throwIfInvalid($validator);

        $data = $request->validate([
            'code' => 'sometimes|nullable|unique:rankings',
            'name' => 'sometimes|nullable|string',
            'creator' => 'sometimes|nullable|exists:teachers,id'
        ]);

        $previousRanking = Ranking::with(['creator', 'students', 'queues'])
            ->firstWhere('code', $code);

        $ranking = Ranking::with(['creator', 'students', 'queues'])
            ->firstWhere('code', $code);

        foreach ($data as $key => $value) {
            if (empty($value)) {
                $ranking->makeHidden($key);
            }
        }

        $ranking->fill($data);
        $success = $ranking->save();

        return response(
            $previousRanking,
            status: $success ? 200 : 422
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
            Ranking::with(['students'])
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
        $rankings = Ranking::with('students')->get();

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

        return response($leaderboards);
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

        $ranking = Ranking::with(['students', 'queues'])
            ->firstWhere('code', $data['code']);

        // Don't repeat same pending/accepted queue for student and ranking
        if (
            Ranking::with([
                'queues' => function ($query) {
                    global $data;

                    return $query
                        ->where('student_id', $data['student_id'])
                        ->where('ranking_id', $data['ranking_id'])
                        ->where('join_status_id', '<=', JoinStatus::Pending->value);
                }
            ])
                ->firstWhere('code', $data['code'])
                ->queues
                ->count() !== 0
        ) {
            return response(status: 422);
        }

//        foreach ($ranking->queues() as $item) {
//            $wantedRelation = $item['student_id'] === $studentId
//                && $item['ranking_id'] === $ranking->id;
//
//            // Accepted & Pending
//            if ($wantedRelation && $item['join_status_id'] <= JoinStatus::Pending) {
//                // Bad Request
//                return response(status: 422);
//            }
//        }

        $ranking->queues()->attach($studentId, [
            'join_status_id' => JoinStatus::Pending->value,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $ranking->students()->attach($studentId, [
            'points' => 0
        ]);

        return response($ranking);
    }
}
