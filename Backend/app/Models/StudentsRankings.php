<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StudentsRankings extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'students_id',
        'rankings_code',
        'rank',
        'points'
    ];

    public static function createFromRequest(Request $request): StudentsRankings
    {
        $data = $request->validate([
            'code' => 'required|uuid',
            'student_id' => ['required', 'exists:students,id',
                Rule::unique('rankings')->where(fn($query) => $query->where('code', $request->code))
            ],
            'rankings_code' => 'required|uuid',
            'rank' => ['required', 'int', 'gt:0',
                Rule::unique('rankings')->where(fn($query) => $query->where('code', $request->code))
            ],
            'points' => 'required|int|gt:0'
        ]);

        return StudentsRankings::create([
            'code' => $data['code']
        ]);
    }

    public static function updateFromRequest($id, Request $request): array|StudentsRankings|null
    {
        $data = $request->validate([
            'code' => 'required|uuid',
            'student_id' => 'required|exists:students,id',
            'rank' => ['required', 'int', 'gt:0',
                Rule::unique('rankings')->where(fn($query) => $query->where('code', $request->code))
            ],
            'points' => 'required|int|gt:0'
        ]);

        $studentsRankings = StudentsRankings::find($id);

        if (!$studentsRankings) {
            return null;
        }

        $oldStudentsRankings = $studentsRankings;
        $studentsRankings->code = $data['code'];
        $studentsRankings->student_id = $data['student_id'];
        $studentsRankings->rank = $data['rank'];
        $studentsRankings->points = $data['points'];
        $studentsRankings->save();

        return $oldStudentsRankings;
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
