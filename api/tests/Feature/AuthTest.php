<?php

use App\Models\User;
use function Pest\Laravel\postJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\actingAs;
use Illuminate\Support\Facades\Hash;

it('registers a user successfully', function () {
    $response = postJson('/api/v1/auth/register', [
        'display_name' => 'Test User',
        'email' => 'TEST@example.com ', // Testing normalization
        'password' => 'password123',
    ]);

    $response->assertStatus(201)
             ->assertJsonPath('data.display_name', 'Test User')
             ->assertJsonPath('data.email', 'test@example.com'); // Asserting it normalized

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
    ]);
});

it('prevents registration with existing email', function () {
    User::factory()->create([
        'email' => 'test@example.com',
    ]);

    $response = postJson('/api/v1/auth/register', [
        'display_name' => 'Another User',
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

it('logs in a user successfully', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
             ->assertJsonPath('data.email', 'test@example.com');
});

it('fails login with incorrect credentials', function () {
    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'wrongpassword',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

it('returns authenticated user data', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->getJson('/api/v1/auth/me');

    $response->assertStatus(200)
             ->assertJsonPath('data.email', $user->email);
});

it('prevents unauthenticated access to me endpoint', function () {
    $response = getJson('/api/v1/auth/me');

    $response->assertStatus(401);
});

it('logs out a user successfully', function () {
    $user = User::factory()->create();

    $response = actingAs($user)->postJson('/api/v1/auth/logout');

    $response->assertStatus(200);
});

it('enforces rate limiting on login', function () {
    for ($i = 0; $i < 5; $i++) {
        postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ])->assertStatus(422); // Assuming invalid credentials or doesn't exist
    }

    // 6th attempt should hit rate limit
    $response = postJson('/api/v1/auth/login', [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(429);
});
