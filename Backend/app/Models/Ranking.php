<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
    ];

    public static function assignStudent($id, Request $request)
    {
        $data = $request->validate([
            'code' => 'required|uuid|exists:rankings,code',
            'points' => 'sometimes|nullable|required|int|gt:0'
        ]);

        $ranking = Ranking::where('code', $data['code'])->first();
        $student = Student::find($id);

        if (!($ranking && $student)) {
            return false;
        }

        return $ranking->students()->attach($student->id, [
            'points' => $data['points'] ?? 0
        ]);
    }

    public static function createFromRequest(Request $request): Ranking
    {
        $data = $request->validate([
            'code' => 'required|uuid|unique:rankings,code'
        ]);

        return Ranking::create([
            'code' => $data['code']
        ]);
    }

    public static function updateFromRequest($id, Request $request): array|Ranking|null
    {
        $data = $request->validate([
            'ranking_code' => 'required|uuid|unique:rankings,code',
            'student_id' => 'required|int|gt:0',
            'points' => 'sometimes|nullable|required|int|gt:0',
        ]);

        $ranking = Ranking::find($id);

        if (!$ranking) {
            return null;
        }

        $oldRanking = $ranking;
        $ranking->id = $data['id'];
        $ranking->pivot->ranking_id = $data['id'];
        $ranking->pivot->student_id = $data['students_id'];
        $ranking->pivot->points = $data['points'] ?? 0;
        $ranking->save();

        return $ranking->getOriginal();
    }

    public function students(): BelongsToMany
    {
        return $this
            ->belongsToMany(Student::class, 'ranking_student', 'ranking_id', 'student_id')
            ->withPivot('points');
    }
}
