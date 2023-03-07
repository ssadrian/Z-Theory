<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Assignment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'content',
        'points'
    ];

    public static function createFromRequest(Request $request): Assignment
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'sometimes|nullable|string',
            'content' => 'sometimes|nullable|string',
            'points' => 'required|int|gte:0'
        ]);

        return Assignment::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'content' => $data['content'],
            'points' => Hash::make($data['points'])
        ]);
    }

    public static function updateFromRequest($id, Request $request): array|null
    {
        $data = $request->validate([
            'name' => 'sometimes|nullable|required|string',
            'description' => 'sometimes|nullable|required|string',
            'content' => 'sometimes|nullable|required|string',
            'points' => 'sometimes|nullable|required|string',
        ]);

        $student = Assignment::find($id);
        if (!$student) {
            return null;
        }

        if (!(empty($data['nickname']) || $student->nickname == $data['nickname'])) {
            $student->nickname = $data['nickname'];
        }

        if (!empty($data['name'])) {
            $student->name = $data['name'];
        }

        if (!empty($data['surnames'])) {
            $student->surnames = $data['surnames'];
        }

        if (!empty($data['birth_date'])) {
            $student->birth_date = $data['birth_date'];
        }

        if (!empty($data['avatar'])) {
            $student->avatar = $data['avatar'];
        }

        $student->save();

        return $student->getOriginal();
    }

    public function rankings(): BelongsToMany
    {
        return $this
            ->belongsToMany(Ranking::class, 'ranking_student', 'student_id', 'ranking_id')
            ->withPivot('points');
    }
}
