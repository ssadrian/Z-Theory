<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        'student_id',
        'rank'
    ];

    public static function createFromRequest(Request $request): Ranking
    {
        $data = $request->validate([
            'code' => 'required|uuid',
            'student_id' => 'required|int|exists:students,id',
            'rank' => ['required', 'int', 'gt:0',
                Rule::unique('rankings')->where(fn($query) => $query->where('code', $request->code))
            ],
        ]);

        return Ranking::create([
            'code' => $data['code'],
            'student_id' => $data['student_id'],
            'rank' => $data['rank']
        ]);
    }

    public static function updateFromRequest(Request $request): array|Ranking|null
    {
        $data = $request->validate([
            'id' => 'required|exists:students',
            'code' => 'required|uuid',
            'student_id' => 'required|int|exists:students,id',
            'rank' => ['required', 'int', 'gt:0',
                Rule::unique('rankings')->where(fn($query) => $query->where('code', $request->code))
            ],
        ]);

        $ranking = Ranking::find($data['id']);
        $oldRanking = $ranking;

        $ranking->student_id = $data['student_id'];
        $ranking->rank = $data['rank'];
        $ranking->code = $data['code'];

        $ranking->save();

        return $oldRanking;
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
