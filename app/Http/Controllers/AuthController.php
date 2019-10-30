<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends Controller
{
       //
     public $successStatus = 200;

     public function register(Request $request) {
     $validator = Validator::make($request->all(),
                 [
                 'nama_depan' => 'required',
                 'nama_belakang' => 'required',
                 'dob' => 'required',
                 'region' => 'required',
                 'email' => 'required|email',
                 'password' => 'required',
                 'c_password' => 'required|same:password',
                     ]);
         if ($validator->fails()) {
             return response()->json(['error'=>$validator->errors()], 401);                        }
         $input = $request->all();
         $input['password'] = bcrypt($input['password']);
         $user = User::create($input);
         $success['token'] =  $user->createToken('AppName')->accessToken;
         return response()->json(['success'=>$success], $this->successStatus);
     }


     public function login(){
             if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
                 $user = Auth::user();
                 $success['token'] =  $user->createToken('AppName')-> accessToken;
                 return response()->json(['success' => $success], $this-> successStatus);
             }
         else{
             return response()->json(['error'=>'Unauthorised'], 401);
         }
     }

     public function getUser() {
         $user = Auth::user();
         return response()->json(['success' => $user], $this->successStatus);
     }


       //

    // public function store(Request $request){
    //     // membuata validasi input

    //     $this->validate($request, [
    //         'name' => 'required',
    //         'email' => 'required|email',
    //         'password' => 'required|min:5'

    //     ]);

    //     $name = $request->input('name');
    //     $email = $request->input('email');
    //     $password = $request->input('password');

    //     $user = new User([
    //         'name' => $name,
    //         'email' => $email,
    //         'password' => bcrypt($password)
    //     ]);
    //     if($user->save()){
    //         $user->signin = [
    //             'href' => 'api/v1/user/signin',
    //             'method' => 'POST',
    //             'params' => 'email, password'
    //         ];
    //         $response = [
    //             'msg' => 'User created',
    //             'user' => $user,
    //             // 'token' => $token
    //         ];
    //         return response()->json($response, 201);
    //     }

    //     $response = [
    //         'msg' => 'An error occured'
    //     ];
    //     return response()->json($response, 404);
    // }

    // public function signin(Request $request){
    //     return 'It works';
    // }
}
