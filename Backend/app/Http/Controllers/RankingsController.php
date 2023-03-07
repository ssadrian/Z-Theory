<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * @return Response|Application|ResponseFactory
     */
    public function store(Request $request): Application|ResponseFactory|Response
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
     * @return Model|Response|Builder|Application|ResponseFactory
     */
    public function show($code): Model|Response|Builder|Application|ResponseFactory
    {
        $ranking = Ranking::with('students')
            ->firstWhere('code', $code);

        if (!$ranking) {
            // No Content
            return response(status: 204);
        }

        return $ranking;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $code
     * @param Request $request
     * @return Response|Application|ResponseFactory
     */
    public function update($code, Request $request): Response|Application|ResponseFactory
    {
        $rank = Ranking::updateFromRequest($code, $request);
        return response($rank);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $code
     * @return Response|Application|ResponseFactory
     */
    public function destroy($code): Response|Application|ResponseFactory
    {
        $rank = Ranking::find($code);
        if (!$rank) {
            return response(status: 204);
        }

        $rank->delete();

        return response(status: 200);
    }

    public function createdBy(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:teachers,id'
        ]);

        $rankings = Ranking::with('students')
            ->where('creator', $data['id'])
            ->get();

        if (!$rankings) {
            // No Content
            return response(status: 204);
        }

        return response($rankings);
    }

    public function forStudent($id)
    {
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

    public function assignStudent(Request $request): Response|Application|ResponseFactory
    {
        Ranking::assignStudent($request);
        return response(status: 200);
    }
}
