<?php

namespace Tests\Feature\Http;

use App\Models\Teacher;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class TeacherTest extends TestCase
{
    use DatabaseTransactions;

    private string $endpoint = '/api/user';
    private string $allEndpoint = '/api/user/all';

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
        $user = Teacher::all()->first();

        $response = $this->json('GET', $this->endpoint,
            data: [
                'id' => $user->id
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
        $user = Teacher::all()->first();

        $response = $this->putJson($this->endpoint, [
            'id' => $user->id,
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
        $user = Teacher::all()->first();

        $response = $this->deleteJson($this->endpoint, [
            'id' => $user->id
        ]);

        $response->assertOk();
    }
}
