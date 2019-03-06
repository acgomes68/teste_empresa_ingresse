<?php

namespace Tests\Feature\User;

Use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class UserDeleteFeatureTest extends TestCase
{

    public function testEndPointDestroy()
    {
        $user = factory(User::class)->create();

        $this->json('DELETE', '/api/users/' . $user->id)
            ->assertStatus(200)
            ->assertJsonStructure(
                ['data', 'status']
            )
            ->assertJson([
                'data'=>"User successfully removed",
                'status'=>true
            ]);
    }    

    public function testEndPointDestroyNotExists() {

        $this->json('DELETE', '/api/users/0')
            ->assertStatus(200)
            ->assertJsonStructure(
                ['data', 'status']
            )
            ->assertExactJson(
                ['data'=>"User not found", 'status'=>false]
            );
    }
}
