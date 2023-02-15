<?php

namespace App\Http\Controllers;

use App\Models\Ranking;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RankingsController extends Controller
{
    public function all(): Collection
    {
        return Ranking::with("student")->get();
    }

    public function get(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|exists:rankings,id',
        ]);

        return Ranking::find($data['id']);
    }

    public function create(Request $request): Response|Application|ResponseFactory
    {
        $rank = Ranking::createFromRequest($request);
        $rank->save();

        // Created
        return response(status: 201);
    }

    public function update(Request $request): Response|Application|ResponseFactory
    {
        $rank = Ranking::updateFromRequest($request);
        return response($rank);
    }

    public function delete(Request $request): Response|Application|ResponseFactory
    {
        $data = $request->validate([
            'id' => 'required|exists:rankings',
        ]);

        $rank = Ranking::find($data['id']);
        $rank->delete();

        return response(status: 200);
    }
}
