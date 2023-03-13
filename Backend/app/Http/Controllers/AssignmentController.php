<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Ranking;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(): Response
    {
        return response(
            Assignment::with(['creator'])->get()
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
            'creator' => 'required|exists:teachers,id',
            'title' => 'required|string',
            'points' => 'required|int',
            'description' => 'sometimes|nullable|string',
            'content' => 'sometimes|nullable|string'
        ]);

        // Model requires the teacher's id, however the user must see it as creator
        $data['teacher_id'] = $data['creator'];

        $assignment = Assignment::create($data);
        return response(
            Assignment::with(['creator'])->find($assignment->id)
            , 201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     * @throws ValidationException
     */
    public function show($id): Response
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:assignments'
        ]);
        $this->throwIfInvalid($validator);

        return response(
            Assignment::with(['creator'])
                ->find($id)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function update($id, Request $request): Response
    {
        // Append assignment's id from url to request's body
        $request['id'] = $id;

        $data = $request->validate([
            'id' => 'required|exists:assignments',
            'title' => 'sometimes|nullable|string',
            'description' => 'sometimes|nullable|string',
            'content' => 'sometimes|nullable|string',
            'points' => 'sometimes|nullable|int',
            'creator' => 'sometimes|nullable|int'
        ]);

        $previousAssignment = Assignment::with(['creator'])->find($id);
        $assignment = Assignment::with(['creator'])->find($id);

        foreach ($data as $key => $value) {
            if (empty($value)) {
                $assignment->makeHidden($key);
            }
        }

        $assignment->fill($data);
        $success = $assignment->save();

        return response(
            $previousAssignment,
            status: $success ? 200 : 422
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function destroy($id): Response
    {
        return response(
            status: Assignment::destroy($id) ? 200 : 422
        );
    }

    public function assignToRanking($rankCode, Request $request): Response
    {
        // Append rank's code from url to request's body
        $request['rank_code'] = $rankCode;

        $data = $request->validate([
            'id' => 'required|exists:assignments',
            'rank_code' => 'required|exists:rankings,code'
        ]);

        Assignment::find($data['id'])
            ->rankingsAssigned()
            ->attach(Ranking::all()->firstWhere('code', $rankCode), [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);

        return response(Assignment::with(['creator'])->find($data['id']));
    }
}
