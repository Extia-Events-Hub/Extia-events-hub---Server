<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Event;
use Laravel\Passport\Passport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }   

    public function test_can_get_all_events()
    {
        Event::factory()->count(5)->create();

        $response = $this->get('/api/v2/events');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'shortDescription',
                        'longDescription',
                        'mode',
                        'startDate',
                        'endDate',
                        'startTime',
                        'endTime',
                        'image',
                        'user_id',
                        'max_participants',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    public function test_can_get_event_by_id()
    {
        $event = Event::factory()->create();

        $response = $this->get('/api/v2/events/' . $event->id);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'data' => [
                    'id',
                    'title',
                    'shortDescription',
                    'longDescription',
                    'mode',
                    'startDate',
                    'endDate',
                    'startTime',
                    'endTime',
                    'image',
                    'user_id',
                    'max_participants',
                    'created_at',
                    'updated_at',
                ],
            ]);
    }
    
}
