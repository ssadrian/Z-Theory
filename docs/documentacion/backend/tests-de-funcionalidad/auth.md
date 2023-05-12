<?php

namespace Tests\Feature\Http;

use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    protected Student $testAuthStudent;
    protected Teacher $testAuthTeacher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testAuthStudent = Student::factory()->createOne();
        $this->testAuthTeacher = Teacher::factory()->createOne();

        $this->testAuthStudent->password_confirmation = $this->testAuthStudent->password;
        $this->testAuthTeacher->password_confirmation = $this->testAuthTeacher->password;
    }

    public function test_invalid_student_login_succeeds()
    {
        $response = $this->postJson('/api/login',
            json_decode($this->testAuthStudent, true));

        $response->assertJsonStructure([
            'msg'
        ]);
    }

    public function test_invalid_teacher_login_succeeds()
    {
        $response = $this->postJson('/api/login',
            json_decode($this->testAuthTeacher, true));

        $response->assertJsonStructure([
            'msg'
        ]);
    }
}
