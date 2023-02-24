<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'code'
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

        Ranking::create([
            'code' => $data['code']
        ]);

        return StudentsRankings::createFromRequest($request);
    }

    public static function updateFromRequest($code, Request $request): array|Ranking|null
    {
        $data = $request->validate([
            'code' => 'required|uuid|unique:rankings',
        ]);

        $ranking = Ranking::find($code);

        if (!$ranking) {
            return null;
        }

        $oldRanking = $ranking;
        $ranking->code = $data['code'];
        $ranking->save();

        return $oldRanking;
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }
}
