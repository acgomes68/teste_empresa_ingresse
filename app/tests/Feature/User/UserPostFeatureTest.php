<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class UserPostFeatureTest extends TestCase
{

    public function testEndPointStore()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => $this->faker->password,
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['name', 'email', 'updated_at', 'created_at', 'id'], 'status',
            ])
            ->assertJson(['data' => [
                'name' => $data['name'],
                'email' => $data['email'],
            ], 'status' => true]);

    }

    public function testEndPointRequireName()
    {
        $data = [
            'name' => null,
            'email' => $this->faker->unique()->email,
            'password' => $this->faker->password,
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['name'], 'status',
            ])
            ->assertExactJson(['data' => [
                'name' => ['The name field is required.'],
            ], 'status' => false]);
    }    

    public function testEndPointRequireEmail()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => null,
            'password' => $this->faker->password,
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['email'], 'status',
            ])
            ->assertExactJson(['data' => [
                'email' => ['The email field is required.'],
            ], 'status' => false]);
    }    

    public function testEndPointRequirePassword()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => null
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['password'], 'status',
            ])
            ->assertExactJson(['data' => [
                'password' => ['The password field is required.'],
            ], 'status' => false]);
    }

    public function testEndPointRequireNameAndEmail()
    {
        $data = [
            'name' => null,
            'email' => null,
            'password' => $this->faker->password,
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['name', 'email'], 'status',
            ])
            ->assertExactJson(['data' => [
                'name' => ['The name field is required.'],
                'email' => ['The email field is required.'],
            ], 'status' => false]);
    }    

    public function testEndPointRequireNameAndPassword()
    {
        $data = [
            'name' => null,
            'email' => $this->faker->unique()->email,
            'password' => null,
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['name', 'password'], 'status',
            ])
            ->assertExactJson(['data' => [
                'name' => ['The name field is required.'],
                'password' => ['The password field is required.'],
            ], 'status' => false]);
    }    

    public function testEndPointRequireEmailAndPassword()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => null,
            'password' => null
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['email', 'password'], 'status',
            ])
            ->assertExactJson(['data' => [
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ], 'status' => false]);
    }    

    public function testEndPointRequireNameEmailAndPassword()
    {
        $data = [
            'name' => null,
            'email' => null,
            'password' => null
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['name', 'email', 'password'], 'status',
            ])
            ->assertExactJson(['data' => [
                'name' => ['The name field is required.'],
                'email' => ['The email field is required.'],
                'password' => ['The password field is required.'],
            ], 'status' => false]);
    }    

    public function testEndPointEmailValidFormat()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->freeEmailDomain,
            'password' => $this->faker->password,
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['email'], 'status',
            ])
            ->assertExactJson(['data' => [
                'email' => ['The email must be a valid email address.'],
            ], 'status' => false]);
    }    

    public function testEndPointEmailUpTo255Chars()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->sentence($nbWords = 200, $variableNbWords = true),
            'password' => $this->faker->password,
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['email'], 'status',
            ])
            ->assertExactJson(['data' => [
                'email' => ['The email may not be greater than 255 characters.', 'The email must be a valid email address.'],
            ], 'status' => false]);
    }    

    public function testEndPointEmailUnique()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => 'xjljkj@email.com',
            'password' => $this->faker->password,
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['name', 'email', 'updated_at', 'created_at', 'id'], 'status',
            ])
            ->assertJson(['data' => [
                'name' => $data['name'],
                'email' => $data['email'],
            ], 'status' => true]);

        $data = [
            'name' => $this->faker->name,
            'email' => 'xjljkj@email.com',
            'password' => $this->faker->password,
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['email'], 'status',
            ])
            ->assertExactJson(['data' => [
                'email' => ['The email has already been taken.'],
            ], 'status' => false]);
    }

    public function testEndPointPasswordAtLeast6Chars()
    {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => '12345',
        ];

        $this->json('POST', '/api/users', $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['password'], 'status',
            ])
            ->assertExactJson(['data' => [
                'password' => ['The password must be at least 6 characters.'],
            ], 'status' => false]);
    }    
}
