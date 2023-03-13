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

    public static function updateFromRequest($code, Request $request): array
    {
        $data = $request->validate([
            'name' => 'sometimes|nullable|required|string|unique:rankings,name',
            'creator' => 'sometimes|nullable|required|exists:teachers,id'
        ]);

        $ranking = Ranking::all()
            ->firstWhere('code', $code);

        if (!(empty($data['name']) || $ranking->name == $data['name'])) {
            $ranking->name = $data['name'];
        }

        if (!(empty($data['creator']) || $ranking->creator == $data['creator'])) {
            $ranking->creator = $data['creator'];
        }

        $original = $ranking->getOriginal();

        $ranking->save();
        return $original;
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
