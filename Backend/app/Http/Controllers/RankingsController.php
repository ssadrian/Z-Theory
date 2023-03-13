<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RankingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection
     */
    public function index(): Collection
    {
        return Ranking::with('students')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $rank = Ranking::createFromRequest($request);
        $rank->save();

        // Created
        return response($rank, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param $code
     * @return Model|Response|JsonResponse
     * @throws ValidationException
     */
    public function show($code): Model|Response|JsonResponse
    {
        $validator = Validator::make(['code' => $code], [
            'code' => 'required|exists:rankings'
        ]);
        $this->throwIfInvalid($validator);

        return Ranking::with('students')
            ->firstWhere('code', $code);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $code
     * @param Request $request
     * @return Ranking|JsonResponse
     * @throws ValidationException
     */
    public function update($code, Request $request): array|JsonResponse
    {
        $validator = Validator::make(['code' => $code], [
            'code' => 'required|exists:rankings'
        ]);
        $this->throwIfInvalid($validator);
        return Ranking::updateFromRequest($code, $request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $code
     * @return Response|JsonResponse
     * @throws ValidationException
     */
    public function destroy($code): Response|JsonResponse
    {
        $validator = Validator::make(['code' => $code], [
           'code' => 'required|exists:rankings'
        ]);
        $this->throwIfInvalid($validator);

        return response(
            status: Ranking::all()->firstWhere('code', $code)->delete() ? 200 : 204
        );
    }

    public function createdBy(Request $request): array
    {
        $data = $request->validate([
            'id' => 'required|exists:teachers'
        ]);

        return Ranking::with('students')
            ->where('creator', $data['id'])
            ->get();
    }

    /**
     * @throws ValidationException
     */
    public function forStudent($id): array
    {
        $validator = Validator::make(['id' => $id], [
           'id' => 'required|exists:students'
        ]);
        $this->throwIfInvalid($validator);

        $leaderboards = [];
        $rankings = Ranking::with('students')->get();

        foreach ($rankings as $ranking) {
            if (!$ranking->students->contains($id)) {
                continue;
            }

            $ranking->students->makeHidden(['email', 'password', 'name', 'surnames']);
            foreach ($ranking->students as $student) {
                $student->pivot->makeHidden(['ranking_id', 'student_id']);
            }

            $leaderboards[] = $ranking;
        }

        return $leaderboards;
    }

    public function assignStudent(Request $request): Response
    {
        Ranking::assignStudent($request);
        return response(status: 200);
    }
}
