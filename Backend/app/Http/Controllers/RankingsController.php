<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use App\Models\Student;
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
        return Ranking::with("students")->get();
    }

    public function get($code): Model|Response|Builder|Application|ResponseFactory
    {
        $ranking = Ranking::with("students")
            ->firstWhere('code', $code);

        if (!$ranking) {
            // No Content
            return response(status: 204);
        }

        return $ranking;
    }

    public function create(Request $request): JsonResponse
    {
        $rank = Ranking::createFromRequest($request);
        $rank->save();

        // Created
        return response()
            ->json($rank, 201);
    }

    public function forStudent($id): array
    {
        $leaderboards = [];

        foreach (Ranking::with('students')->get() as $ranking) {
            if (!$ranking->students->contains($id)) {
                continue;
            }

            $leaderboards[] = $ranking;
        }

        return $leaderboards;
    }

    public function assignStudent($id, Request $request): Response|Application|ResponseFactory
    {
        $assignmentDone = Ranking::assignStudent($id, $request);
        if (!$assignmentDone) {
            // Bad Request
            return response(status: 422);
        }

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
