<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;

class Ranking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'creator'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
    ];

    public static function assignStudent(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:students,id',
            'code' => 'required|uuid|exists:rankings,code'
        ]);

        $ranking = Ranking::where('code', $data['code'])->first();
        $student = Student::find($data['student_id']);

        return $ranking->students()->attach($student->id, [
            'points' => 0
        ]);
    }

    public static function createFromRequest(Request $request): Ranking
    {
        $data = $request->validate([
            'code' => 'required|uuid|unique:rankings,code',
            'name' => 'required|string|unique:rankings,name',
            'creator' => 'required|exists:teachers,id'
        ]);

        $ranking = Ranking::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'creator' => $data['creator']
        ]);

        return Ranking::find($ranking->id);
    }

    public static function updateFromRequest($id, Request $request): array|Ranking|null
    {
        $data = $request->validate([
            'ranking_code' => 'required|uuid|unique:rankings,code',
            'student_id' => 'required|int|gt:0',
            'points' => 'sometimes|nullable|required|int|gt:0',
            'name' => 'required|text|unique:rankings,name',
            'creator' => 'required|unique:teachers,id'
        ]);

        $ranking = Ranking::find($id);

        if (!$ranking) {
            return null;
        }

        $oldRanking = $ranking;

        $ranking->id = $data['id'];
        $ranking->name = $data['name'];
        $ranking->creator = $data['creator'];

        $ranking->pivot->ranking_id = $data['id'];
        $ranking->pivot->student_id = $data['students_id'];
        $ranking->pivot->points = $data['points'] ?? 0;
        $ranking->save();

        return $oldRanking;
    }

    public function students(): BelongsToMany
    {
        return $this
            ->belongsToMany(Student::class, 'ranking_student', 'ranking_id', 'student_id')
            ->withPivot('points');
    }

    public function creator(): BelongsTo
    {
        return $this
            ->belongsTo(Teacher::class);
    }
}
