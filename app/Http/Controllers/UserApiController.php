<?php

namespace App\Http\Controllers;

use App\Models\User;
use Dotenv\Repository\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
 

use function Laravel\Prompts\password;

class UserApiController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|string|max:255',
            'email'=>'required|email|max:255|unique:users',
            'tel'=>'required|string',
            'password'=>'required|string|min:8',
            'role'=>'required|string|max:255',
            'image'=>'nullable|image|mimes:jpg,png,jpeg,svg,webp,gif|max:2028'

        ]);

        $default_image ='image.jpg';

        $imagePath =$default_image;

        if($request->has('image')){
            $imagePath = $request->file('image')->store('images','public');
            
        }
        $user = User::create([
                'name'=>$request->input('name'),
                'email'=>$request->input('email'),
                'tel'=>$request->input('tel'),
                'password'=>Hash::make($request->input('password')),
                'role'=>$request->input('role'),
                'image'=>$imagePath

        ]);
        

        return response()->json(['message'=>'a user has been created seccusefully','user'=>$user],201);
    }

    public function login(Request $request)
    {
         $request->validate([
            'email'=>'required|email|max:255',
            'password'=>'required|string|min:8'
        ]);

        $user =User::where('email',$request->input('email'))->first();

        if(!$user || !Hash::check($user->password,$request->input('password'))){
            return response()->json(['error'=>'your email or password is uncorrect try again '],401);
        }

        $user->currentAccessToken()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message'=>'you loged in succesfully',
            'user'=>$user,
            'token'=>$token
        ],200);

    }

    public function logout(Request $request)
    {
       $request->user()->currentAccessToken()->delete();
       
       return response()->json(['message'=>'you loged out seccusfully'],200);
    }
}

