<?php

namespace Tests\Feature\User;

Use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class UserGetFeatureTest extends TestCase
{

    public function testEndPointIndex() {
        factory(User::class)->create(
            ['name' => 'Primeiro Nome', 'email' => 'primeiro@email.com']
        );

        factory(User::class)->create(
            ['name' => 'Segundo Nome', 'email' => 'segundo@email.com']
        );

        $this->json('GET', '/api/users/', [])
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [['id', 'name', 'email', 'created_at', 'updated_at']], 'status',
            ])
            ->assertJson(['data' => [
                    [ 'name' => 'Primeiro Nome', 'email' => 'primeiro@email.com' ],
                    [ 'name' => 'Segundo Nome', 'email' => 'segundo@email.com' ]
                ],'status' => true
            ]);
    }

    public function testEndPointShowExists() {
        $user = factory(User::class)->create();

        $this->json('GET', '/api/users/' . $user->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'name', 'email', 'created_at', 'updated_at'], 'status',
            ])
            ->assertJson(['data' =>
                [ 'name' => $user->name, 'email' => $user->email ],
                'status' => true,
            ]);
    }

    public function testEndPointShowNotExists() {

        $this->json('GET', '/api/users/0')
            ->assertStatus(200)
            ->assertJsonStructure(
                ['data', 'status']
            )
            ->assertExactJson(
                [ 'data' => 'User not found', 'status' => false ]
            );
    }

    public function testEndPointInvalid() {
        $arrEndPoints = array('/', '/user', '/register', '/login', '/home', '/users', '/api');

        foreach($arrEndPoints as $endpoint) {
            $response = $this->get($endpoint);
            $response->assertStatus(404);
        }
    }    
}
