<?php

namespace Tests\Feature\Http;

use App\Models\Teacher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Carbon;

class TeacherTest extends TestCase
{
    use DatabaseTransactions;

    private string $endpoint = '/api/teacher';
    private string $allEndpoint = '/api/teacher/all';

    protected function setUp(): void
    {
        parent::setUp();

        Teacher::factory()->createOne();
    }

    public function test_all_teachers_succeeds()
    {
        $response = $this->getJson($this->allEndpoint);

        $response->assertOk();
    }

    public function test_get_valid_id_succeeds()
    {
        $teacher = Teacher::all()->first();

        $response = $this->json('GET', $this->endpoint,
            data: [
                'id' => $teacher->id
            ]);

        $response->assertOk();
    }

    public function test_create_valid_teacher_succeeds()
    {
        $response = $this->postJson($this->endpoint, [
            'nickname' => 'Test',
            'name' => 'Test',
            'surnames' => 'Test',
            'email' => 'Test@example.com',
            'password' => 'Test',
            'password_confirmation' => 'Test',
            'center' => 'Test'
        ]);

        $response->assertCreated();
    }

    public function test_update_valid_teacher_succeeds()
    {
        $teacher = Teacher::all()->first();

        $response = $this->putJson($this->endpoint, [
            'id' => $teacher->id,
            'nickname' => 'Test',
            'name' => 'Test',
            'surnames' => 'Test',
            'email' => 'test@example',
            'password' => 'test',
            'center' => 'Test'
        ]);

        $response->assertOk();
    }

    public function test_delete_valid_id_succeeds()
    {
        $teacher = Teacher::all()->first();

        $response = $this->deleteJson($this->endpoint, [
            'id' => $teacher->id
        ]);

        $response->assertOk();
    }
}
