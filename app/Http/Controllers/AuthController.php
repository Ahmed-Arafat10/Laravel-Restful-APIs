<?php

namespace App\Http\Controllers;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use HttpResponses;
    public function login(Request $request)
    {
        return $this->successResponse('yes');
    }
    public function register(Request $request)
    {
        return $this->successResponse('yes123',404);
    }
    public function logout(Request $request)
    {
        return $this->successResponse('yes123',404);
    }
}
