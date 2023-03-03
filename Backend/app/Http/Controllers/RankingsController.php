<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RankingsController extends Controller
{
    public function all(): Collection
    {
        return Ranking::with('students')->get();
    }

    public function get($code): Model|Response|Builder|Application|ResponseFactory
    {
        $ranking = Ranking::with('students')
            ->firstWhere('code', $code);

        if (!$ranking) {
            // No Content
            return response(status: 204);
        }

        return $ranking;
    }

    public function create(Request $request): Application|ResponseFactory|Response
    {
        $rank = Ranking::createFromRequest($request);
        $rank->save();

        // Created
        return response($rank, 201);
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

    public function update($code, Request $request): Response|Application|ResponseFactory
    {
        $rank = Ranking::updateFromRequest($code, $request);
        return response($rank);
    }

    public function delete($code): Response|Application|ResponseFactory
    {
        $rank = Ranking::find($code);
        if (!$rank) {
            return response(status: 204);
        }

        $rank->delete();

        return response(status: 200);
    }
}
