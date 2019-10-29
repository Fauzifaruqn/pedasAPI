<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //

    public function store(Request $request){
        // membuata validasi input

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:5'

        ]);

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');

        $user = new User([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password)
        ]);
        if($user->save()){
            $user->signin = [
                'href' => 'api/v1/user/signin',
                'method' => 'POST',
                'params' => 'email, password'
            ];
            $response = [
                'msg' => 'User created',
                'user' => $user,
                // 'token' => $token
            ];
            return response()->json($response, 201);
        }

        $response = [
            'msg' => 'An error occured'
        ];
        return response()->json($response, 404);
    }

    public function signin(Request $request){
        return 'It works';
    }
}
