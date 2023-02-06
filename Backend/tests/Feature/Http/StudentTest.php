<?php

namespace Tests\Feature\Http;

use App\Models\Student;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Carbon;

class StudentTest extends TestCase
{
    use DatabaseTransactions;

    private string $endpoint = "/api/student";
    private string $allEndpoint = "/api/student/all";

    protected function setUp(): void
    {
        parent::setUp();

        Student::factory()->createOne();
    }

    public function test_all_students_succeeds()
    {
        $response = $this->getJson($this->allEndpoint);

        $response->assertOk();
    }

    public function test_get_valid_id_succeeds()
    {
        $student = Student::all()->first();

        $response = $this->json("GET", $this->endpoint,
            data: [
                'id' => $student->id
            ]);

        $response->assertOk();
    }

    public function test_create_valid_student_succeeds()
    {
        $response = $this->postJson($this->endpoint, [
            'nickname' => 'Test',
            'name' => 'Test',
            'surnames' => 'Test',
            'email' => 'Test@example.com',
            'password' => 'Test',
            'password_confirmation' => 'Test',
            'birth_date' => Carbon::now()->format('Y-m-d')
        ]);

        $response->assertCreated();
    }

    public function test_update_valid_student_succeeds()
    {
        $student = Student::all()->first();

        $response = $this->putJson($this->endpoint, [
            'id' => $student->id,
            'nickname' => 'Test',
            'name' => 'Test',
            'surnames' => 'Test',
            'email' => 'test@example',
            'password' => 'test',
            'birth_date' => Carbon::now()->format('Y-m-d')
        ]);

        $response->assertOk();
    }

    public function test_delete_valid_id_succeeds()
    {
        $student = Student::all()->first();

        $response = $this->deleteJson($this->endpoint, [
            'id' => $student->id
        ]);

        $response->assertOk();
    }
}
