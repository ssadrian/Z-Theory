<?php

namespace Tests\Feature\Http;

use App\Models\Ranking;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RankingTest extends TestCase
{
    use DatabaseMigrations;

    private string $endpoint = '/api/ranking';
    private string $allEndpoint = '/api/ranking/all';

    protected function setUp(): void
    {
        parent::setUp();
        Ranking::factory()->createOne();
    }

    public function test_all_ranks_succeeds()
    {
        $response = $this->getJson($this->allEndpoint);

        $response->assertOk();
    }

    public function test_get_valid_id_succeeds()
    {
        $ranking = Ranking::all()->first();

        $response = $this->json('GET', $this->endpoint,
            data: [
                'id' => $ranking->id
            ]);

        $response->assertOk();
    }

    public function test_create_valid_ranking_succeeds()
    {
        $response = $this->postJson($this->endpoint, [
            'student_id' => 1,
            'rank' => 1
        ]);

        $response->assertCreated();
    }

    public function test_update_valid_ranking_succeeds()
    {
        $ranking = Ranking::all()->first();

        $response = $this->putJson($this->endpoint, [
            'id' => $ranking->id,
            'student_id' => 2,
            'ranking' => 3
        ]);

        $response->assertOk();
    }

    public function test_delete_valid_id_succeeds()
    {
        $rank = Ranking::all()->first();

        $response = $this->deleteJson($this->endpoint, [
            'id' => $rank->id
        ]);

        $response->assertOk();
    }
}
