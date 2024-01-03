<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([

            'email' => "required|email",
            'password' => "required|min:6"
        ]);

        $email = User::where("email", $request->email)->get();
        if (count($email) > 0) {
            return response()->json([
                "status" => 404,
                "message" => "Email is exist"
            ]);
        } else {
            $user = User::create([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password)
            ]);
            $token = $user->createToken('TOKEN')->accessToken;

            $cookie = cookie("key", $token, 60 * 720);

            return response([
                "user" => $user,
                "token" => $token

            ])->withCookie($cookie);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('TOKEN')->accessToken;
                $cookie = cookie("key", $token, 60 * 720);
                return response(['user' => $user, 'token' => $token , 'status'=> 200])->withCookie($cookie);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" => 'User does not exist'];
            return response($response, 422);
        }
    }
    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
            Cookie::queue(Cookie::forget('key'));
        });
        return response(['message' => 'logout']);
    }
}
