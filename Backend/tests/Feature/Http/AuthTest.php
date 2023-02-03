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

    public function test_invalid_login_succeeds()
    {
        $response = $this->postJson('/api/login',
            json_decode($this->testAuthStudent, true));

        $response->assertJsonStructure([
            'msg'
        ]);
    }

    public function test_valid_student_registration_succeeds()
    {
        $response = $this->postJson('/api/register/student',
            json_decode($this->testAuthStudent, true));

        $response
            ->assertStatus(201);
    }

    public function test_valid_teacher_registration_succeeds()
    {
        $response = $this->postJson('/api/register/teacher',
            json_decode($this->testAuthTeacher, true));

        $response
            ->assertStatus(201);
    }
}
