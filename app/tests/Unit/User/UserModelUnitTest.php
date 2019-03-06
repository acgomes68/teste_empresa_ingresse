<?php

// $this->assertDirectoryIsWritable('/path/to/directory');

namespace Tests\Unit\User;

Use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;


class UserModelUnitTest extends TestCase
{
    public function testShowAllItems() {
        $arrData = $arrUsers = array();

        for ($i=0; $i<10; $i++) {
            $arrData[] = factory(User::class)->create();
        }

        $objUsers = new User();
        $arrUsers = $objUsers->all();

        $this->assertInstanceOf(User::class, $objUsers);

        for ($i=0; $i<10; $i++) {
            $this->assertEquals($arrData[$i]['id'], $arrUsers[$i]['id']);
            $this->assertEquals($arrData[$i]['name'], $arrUsers[$i]['name']);
            $this->assertEquals($arrData[$i]['email'], $arrUsers[$i]['email']);
            $this->assertEquals($arrData[$i]['created_at'], $arrUsers[$i]['created_at']);
            $this->assertEquals($arrData[$i]['updated_at'], $arrUsers[$i]['updated_at']);
        }
    }

    public function testShowAnItem() {
        $user = $userRepo = factory(User::class)->create();
        $found = $userRepo->find($user->id);
        
        $this->assertInstanceOf(User::class, $found);
        $this->assertEquals($found->name, $user->name);
        $this->assertEquals($found->email, $user->email);
        $this->assertEquals($found->password, $user->password);
    }

    public function testCreate() {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => $this->faker->password,
        ];

        $user = new User();
        $user = $user->create($data);
      
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertEquals($data['password'], $user->password);
    }

    public function testUpdate() {
        $dataCreate = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => $this->faker->password,
        ];

        $objUser = new User();
        $userCreate = $objUser->create($dataCreate);
        $id = $userCreate->id;

        $dataUpdate = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => $this->faker->password,
        ];

        $userUpdate = User::find($id);
        $userUpdate->update($dataUpdate);

        $this->assertInstanceOf(User::class, $objUser);
        $this->assertInstanceOf(User::class, $userUpdate);
        $this->assertEquals($dataUpdate['name'], $userUpdate->name);
        $this->assertEquals($dataUpdate['email'], $userUpdate->email);
        $this->assertEquals($dataUpdate['password'], $userUpdate->password);
    }

    public function testDelete() {
        $user = $userRepo = factory(User::class)->create();
        $user->find($userRepo->id);
        $delete = $user->delete();

        $objUser = new User();
        $userDelete = $objUser->find($userRepo->id);

        $this->assertInstanceOf(User::class, $objUser);
        $this->assertInstanceOf(User::class, $user);
        $this->assertTrue($delete);
        $this->assertNull($userDelete);
    }
}
