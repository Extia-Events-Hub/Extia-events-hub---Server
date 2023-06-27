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

    // public function test_can_create_event()
    // {
    //     Storage::fake('public');

    //     $user = User::factory()->create();

    //     $data = [
    //         'title' => $this->faker->sentence,
    //         'shortDescription' => $this->faker->paragraph,
    //         'longDescription' => $this->faker->text,
    //         'mode' => 'Mode 1',
    //         'startDate' => now()->format('Y-m-d'),
    //         'endDate' => now()->addDays(7)->format('Y-m-d'),
    //         'startTime' => $this->faker->time('H:i'),
    //         'endTime' => $this->faker->time('H:i'),
    //         'image' => UploadedFile::fake()->image('event.jpg'),
    //         'user_id' => $user->id,
    //         'max_participants' => 50,
    //     ];

    //     $response = $this->post('/api/v2/events', $data);

    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'status' => true,
    //             'message' => 'New event created successfully',
    //         ])
    //         ->assertJsonStructure([
    //             'status',
    //             'message',
    //             'event' => [
    //                 'id',
    //                 'title',
    //                 'shortDescription',
    //                 'longDescription',
    //                 'mode',
    //                 'startDate',
    //                 'endDate',
    //                 'startTime',
    //                 'endTime',
    //                 'image',
    //                 'user_id',
    //                 'max_participants',
    //                 'created_at',
    //                 'updated_at',
    //             ],
    //         ]);

    //     Storage::disk('public')->assertExists($response['event']['image']);
    // }

    public function testCanCreateEvent()
    {
        $user = User::factory()->create(['role' => '1']);
        $token = $user->createToken('test_token')->accessToken;
        Passport::actingAs($user);

        Storage::fake('public');

        $image = UploadedFile::fake()->image('event-image.jpg');

        $data = [
            'title' => 'Test Event',
            'shortDescription' => 'Short description',
            'longDescription' => 'Long description',
            'mode' => 'online',
            'startDate' => '2023-06-27',
            'endDate' => '2023-06-28',
            'startTime' => '09:00',
            'endTime' => '17:00',
            'image' => $image,
            'user_id' => 1,
            'max_participants' => 100,
        ];

        // Send the request with authentication headers
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/v2/events', $data);

        // Assert the response
        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'New event created successfully',
            ]);

        Storage::disk('public')->assertExists('events/' . $image->hashName());
    }

   

    // public function testCanCreateEvent()
    // {
    //     // Create a user and authenticate
    //     $user = User::factory()->create(['role' => '1']);
    //     $token = $user->createToken('test_token')->accessToken;
    //     Passport::actingAs($user);

    //     // Define the event data with the image attribute
    //     $data = [
    //         'title' => 'Test Event',
    //         'shortDescription' => 'Short description',
    //         'longDescription' => 'Long description',
    //         'mode' => 'online',
    //         'startDate' => '2023-06-28',
    //         'endDate' => '2023-06-30',
    //         'startTime' => '09:00',
    //         'endTime' => '17:00',
    //         'image' => UploadedFile::fake()->image('event.jpg'),
    //         'user_id' => $user->id,
    //         'max_participants' => 100,
    //     ];

    //     // Send the request with authentication headers
    //     $response = $this->withHeaders([
    //         'Authorization' => 'Bearer ' . $token,
    //     ])->post('/api/v2/events', $data);

    //     // Assert the response
    //     $response->assertStatus(200)
    //         ->assertJson([
    //             'status' => true,
    //             'message' => 'New event created successfully',
    //         ]);

    //     // Print the response content for debugging
    //     $response->dump();
    //     $responseData = $response->json();
    //     $this->assertArrayHasKey('error', $responseData, 'Error key not found in response.');
    //     $this->assertNotNull($responseData['error'], 'Error message is null.');
    
    //     // Output the error message for further investigation
    //     echo $responseData['error'];
    // }

    public function test_can_store_an_event()
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => '1']);
        $token = $user->createToken('test_token')->accessToken;
        Passport::actingAs($user);

        $image = UploadedFile::fake()->image('event-image.jpg');

        $data = [
            'title' => 'Test Event',
            'shortDescription' => 'Short description',
            'longDescription' => 'Long description',
            'mode' => 'online',
            'startDate' => '2023-06-27',
            'endDate' => '2023-06-28',
            'startTime' => '09:00',
            'endTime' => '17:00',
            'image' => $image,
            'user_id' => 1,
            'max_participants' => 100,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/v2/events', $data);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'New event created successfully',
            ]);

        $this->assertDatabaseHas('events', [
            'title' => 'Test Event',
        ]);

        Storage::disk('public')->assertExists('events/' . $image->hashName());
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
