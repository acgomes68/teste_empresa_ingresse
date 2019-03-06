<?php

namespace App\Http\Controllers;

Use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected function validator(Request $request) {
        return Validator::make(
            $request->all(),
            [
                'name'      => 'required|string|max:255',
                'email'     => 'required|string|email|max:255|unique:users',
                'password'  => 'required|string|min:6',
            ]
        );
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json(['data'=>$users, 'status'=>true]);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $this->validator($request);

        if ($validator->fails()) {
            return response()->json(['data'=>$validator->errors(), 'status'=>false]);
        }
        else {
            $data = $request->all();

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            if ($user) {
                return response()->json(['data'=>$user, 'status'=>true]);
            }
            else {
                return response()->json(['data'=>'Error creating User', 'status'=>false]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json(['data'=>$user, 'status'=>true]);
        }
        else {
            return response()->json(['data'=>'User not found', 'status'=>false]);   
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $requestValidate = array();

        $user = User::find($id);
        if ($user) {
            $validator = $this->validator($request);

            if ($validator->fails()) {
                return response()->json(['data'=>$validator->errors(), 'status'=>false]);
            }
            else {
                $data = $request->all();

                $user->update([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                ]);
                if ($user) {
                    return response()->json(['data'=>$user, 'status'=>true]);
                }
                else {
                    return response()->json(['data'=>'Error updating User', 'status'=>false]);   
                }
            }

        }
        else {
            return response()->json(['data'=>'User not found', 'status'=>false]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete($id);
            if ($user) {
                return response()->json(['data'=>"User successfully removed", 'status'=>true]);
            }
            else {
                return response()->json(['data'=>'Error deleting User', 'status'=>false]);
            }
        }
        else {
            return response()->json(['data'=>'User not found', 'status'=>false]);
        }
    }
}
