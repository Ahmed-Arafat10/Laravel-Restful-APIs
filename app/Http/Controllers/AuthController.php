<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(UserRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password'])))
            return $this->errorResponse('', 'credential do not match', 401);

        $user = User::where('email', $request->email)->first();
        return $this->successResponse([
            'user' => $user,
            'token' => $user->createToken("Login Token for user with ID " . $user->id)->plainTextToken
        ], 'Success Login', 200);
    }

    public function register(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        return $this->successResponse([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ], 'Success Register', 201);
    }// 4|BspV6YJcRBo3R4AdAgZnT7g3WBJ4Jo0bsWReOSkt724abf01

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->successResponse('', 'Logged Out Successfully');
    }
}
