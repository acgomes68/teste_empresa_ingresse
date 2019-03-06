<?php

namespace Tests\Unit\User;

use App\Http\Controllers\UserController;
Use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Request;


class UserControllerUnitTest extends TestCase
{

    public function testIndex() {
        $arrDataCreate = array();
        $arrDataCreate[] = ['name' => 'Primeiro Nome', 'email' => 'primeiro@email.com'];
        $arrDataCreate[] = ['name' => 'Segundo Nome', 'email' => 'segundo@email.com'];

        foreach ($arrDataCreate as $jsonDataCreate) {
            factory(User::class)->create(
                $jsonDataCreate
            );
        }

        $objUserController = new UserController();
        $arrContent = $objUserController->index()->content();
        $arrUsers = json_decode($arrContent, true);
        $arrUsers = $arrUsers['data'];

        $this->assertInstanceOf(UserController::class, $objUserController);

        for ($i=0; $i<count($arrUsers); $i++) {
            $this->assertEquals($arrUsers[$i]['name'], $arrDataCreate[$i]['name']);
            $this->assertEquals($arrUsers[$i]['email'], $arrDataCreate[$i]['email']);
        }
    }    

    public function testStore() {
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => $this->faker->password,
        ];

        $objRequest = new Request();
        $objRequest->setMethod('POST');
        $objRequest->request->add($data);

        $objUserController = new UserController();
        $arrContent = $objUserController->store($objRequest)->content();
        $arrUsers = json_decode($arrContent, true);
        $arrUsers = $arrUsers['data'];

        $this->assertInstanceOf(UserController::class, $objUserController);
        $this->assertEquals($arrUsers['name'], $data['name']);
        $this->assertEquals($arrUsers['email'], $data['email']);
    }

    public function testShow() {
        $user = $userRepo = factory(User::class)->create();
        $found = $userRepo->find($user->id);

        $objUserController = new UserController();
        $arrContent = $objUserController->show($found->id)->content();
        $arrUsers = json_decode($arrContent, true);
        $arrUsers = $arrUsers['data'];

        $this->assertInstanceOf(User::class, $found);
        $this->assertInstanceOf(UserController::class, $objUserController);
        $this->assertEquals($found->name, $arrUsers['name']);
        $this->assertEquals($found->email, $arrUsers['email']);
        $this->assertEquals($found->created_at, $arrUsers['created_at']);
        $this->assertEquals($found->updated_at, $arrUsers['updated_at']);
    }    

    public function testUpdate()
    {
        $objUser = $objUserRepo = factory(User::class)->create();
        $found = $objUserRepo->find($objUser->id);

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->email,
            'password' => $this->faker->password,
        ];

        $objRequest = new Request();
        $objRequest->setMethod('PUT');
        $objRequest->request->add($data);

        $objUserController = new UserController();
        $arrContent = $objUserController->update($objRequest, $objUser->id)->content();
        $arrUsers = json_decode($arrContent, true);
        $arrUsers = $arrUsers['data'];

        $this->assertInstanceOf(User::class, $objUser);
        $this->assertInstanceOf(UserController::class, $objUserController);
        $this->assertEquals($data['name'], $arrUsers['name']);
        $this->assertEquals($data['email'], $arrUsers['email']);
    }    

    public function testDestroy() {
        $user = $userRepo = factory(User::class)->create();
        $user->find($userRepo->id);

        $objUserController = new UserController();
        $arrContent = $objUserController->destroy($user->id)->content();
        $arrUsers = json_decode($arrContent, true);
        $strDelete = $arrUsers['data'];

        $objUserController = new UserController();
        $arrContent = $objUserController->show($user->id)->content();
        $arrUsers = json_decode($arrContent, true);
        $strConfirmDelete = $arrUsers['data'];

        $this->assertInstanceOf(UserController::class, $objUserController);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($strDelete, 'User successfully removed');
        $this->assertEquals($strConfirmDelete, 'User not found');
    }


    public function testDestroyNotExists() {
        $objUserController = new UserController();
        $arrContent = $objUserController->destroy(0)->content();
        $arrUsers = json_decode($arrContent, true);
        $strDelete = $arrUsers['data'];

        $this->assertInstanceOf(UserController::class, $objUserController);
        $this->assertEquals($strDelete, 'User not found');
    }

}
