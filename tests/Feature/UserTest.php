<?php

namespace Tests\Feature;

use DateTime;
use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\ClientRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }

    public function test_user_can_register(): void
    {
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password123',
            'role' => '1',
        ];

        $response = $this->post('/api/v2/register', $userData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User created successfully',
            ]);

        $user = User::where('email', $userData['email'])->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check($userData['password'], $user->password));
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $loginData = [
            'email' => $user->email,
            'password' => 'password123',
        ];

        $response = $this->post('/api/v2/login', $loginData);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'message' => 'User Logged In successfully',
            ])
            ->assertJsonStructure([
                'status',
                'message',
                'access_token',
            ]);

        $this->assertAuthenticated();
    }

    public function test_user_can_logout(): void
    {        
        $user = User::factory()->create();

        Passport::actingAs($user);        

        $response = $this->actingAs($user)
            ->get('/api/v2/logout');

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Logged Out Successfully',
            ]);

        $this->assertEmpty($user->tokens);
    }

    public function test_user_with_superadmin_role_can_retrieve_user_by_id(): void
    {
        $user = User::factory()->create(['role' => '1']);

        Passport::actingAs($user);

        $response = $this->get('/api/v2/users/' . $user->id);

        $response->assertStatus(201);

        $response->assertJson($user->toArray());
    }

    public function test_user_can_retrieve_users_paginated(): void
    {
        $user = User::factory()->create(['role' => '1']);

        Passport::actingAs($user);

        $response = $this->get('/api/v2/users');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'status',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                    ],
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links' => [
                    '*' => [
                        'url',
                        'label',
                        'active',
                    ],
                ],
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total',
            ],
        ]);
    }
}
