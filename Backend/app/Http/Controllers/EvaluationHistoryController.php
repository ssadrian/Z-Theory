<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class EvaluationHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    static public function store(array $data)
    {
        Validator::validate($data, [
            'evaluator' => 'required|exists:students,id',
            'subject' => 'required|different:evaluator|exists:students,id',
            'skill' => 'required|exists:skills,id',
            'kudos' => 'required|int|gt:0'
        ]);

        Student::find($data['evaluator'])
            ->evaluationHistory()
            ->syncWithoutDetaching([
                $data['skill'] => [
                    'subject' => $data['subject'],
                    'kudos' => $data['kudos'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    static public function destroy(array $data)
    {
        //
    }
}
