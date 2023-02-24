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
    public function all(): Collection
    {
        return Ranking::with("students")->get();
    }

    public function get($code): Model|Response|Builder|Application|ResponseFactory
    {
        $ranking = Ranking::with("students")->find($code);

        if (!$ranking) {
            // No Content
            return response(status: 204);
        }

        return $ranking;
    }

    public function create(Request $request): Response|Application|ResponseFactory
    {
        $rank = Ranking::createFromRequest($request);
        $rank->save();

        // Created
        return response(status: 201);
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
