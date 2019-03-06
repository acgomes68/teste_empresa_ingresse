<?php

namespace Tests\Feature\User;

Use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class UserPutFeatureTest extends TestCase
{

    public function testEndPointUpdate()
    {
        $user = factory(User::class)->create();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => $this->faker->password,
        ];

        $this->json('PUT', '/api/users/' . $user->id, $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['name', 'email', 'updated_at', 'created_at', 'id'], 'status',
            ])
            ->assertJson(['data' => [
                'name' => $data['name'],
                'email' => $data['email'],
            ], 'status' => true]);
    }    

    public function testEndPointUpdateNotExists() {

        $user = factory(User::class)->create();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => $this->faker->password,
        ];

        $this->json('PUT', '/api/users/0', $data)
            ->assertStatus(200)
            ->assertJsonStructure(
                ['data', 'status']
            )
            ->assertExactJson(
                ['data'=>"User not found", 'status'=>false]
            );
    }

    public function testEndPointRequireName()
    {
        $user = factory(User::class)->create();

        $data = [
            'name' => null,
            'email' => $this->faker->unique()->email,
            'password' => $this->faker->password,
        ];

        $this->json('PUT', '/api/users/' . $user->id, $data)
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
        $user = factory(User::class)->create();

        $data = [
            'name' => $this->faker->name,
            'email' => null,
            'password' => $this->faker->password,
        ];

        $this->json('PUT', '/api/users/' . $user->id, $data)
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
        $user = factory(User::class)->create();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => null
        ];

        $this->json('PUT', '/api/users/' . $user->id, $data)
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
        $user = factory(User::class)->create();

        $data = [
            'name' => null,
            'email' => null,
            'password' => $this->faker->password,
        ];

        $this->json('PUT', '/api/users/' . $user->id, $data)
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
        $user = factory(User::class)->create();

        $data = [
            'name' => null,
            'email' => $this->faker->unique()->email,
            'password' => null,
        ];

        $this->json('PUT', '/api/users/' . $user->id, $data)
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
        $user = factory(User::class)->create();

        $data = [
            'name' => $this->faker->name,
            'email' => null,
            'password' => null
        ];

        $this->json('PUT', '/api/users/' . $user->id, $data)
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
        $user = factory(User::class)->create();

        $data = [
            'name' => null,
            'email' => null,
            'password' => null
        ];

        $this->json('PUT', '/api/users/' . $user->id, $data)
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
        $user = factory(User::class)->create();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->freeEmailDomain,
            'password' => $this->faker->password,
        ];

        $this->json('PUT', '/api/users/' . $user->id, $data)
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
        $user = factory(User::class)->create();

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->sentence($nbWords = 200, $variableNbWords = true),
            'password' => $this->faker->password,
        ];

        $this->json('PUT', '/api/users/' . $user->id, $data)
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
        $user = factory(User::class)->create();

        $data = [
            'name' => $this->faker->name,
            'email' => 'xjljkj@email.com',
            'password' => $this->faker->password,
        ];

        $this->json('PUT', '/api/users/' . $user->id, $data)
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

        $this->json('PUT', '/api/users/' . $user->id, $data)
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
        $user = factory(User::class)->create();
        
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => '12345',
        ];

        $this->json('PUT', '/api/users/' . $user->id, $data)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['password'], 'status',
            ])
            ->assertExactJson(['data' => [
                'password' => ['The password must be at least 6 characters.'],
            ], 'status' => false]);
    }    
}
