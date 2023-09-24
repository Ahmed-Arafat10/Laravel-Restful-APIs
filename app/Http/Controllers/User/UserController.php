<?php

namespace App\Http\Controllers\User;

use App\Exceptions\customFormValidationException;
use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware(['client.credentials'])->only(['index']);
        $this->middleware(['auth:api'])->only(['show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$user = User::all();
        $user = User::paginate(10);
        return $this->showAllPaginate($user);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails())
            throw new customFormValidationException($validator, $request);
        //return $this->errorResponse($validator->getMessageBag(), 400);


        $data = $request->all(); # creating a new array
        $data['password'] = Hash::make($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;
        $user = User::create($data);
        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)// implicit model binding
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // DANGER: should add an exception if user does not exist
        $rules = [
            'email' => 'email|' . Rule::unique('users', 'email')->ignore($user->id), // unique:users,email,' . $user->id
            'password' => 'min:6|confirmed',
            'admin' => Rule::in([User::ADMIN_USER, User::REGULAR_USER]), // 'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER
        ];

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails())
            return $this->errorResponse($validate->getMessageBag(), 400);

        if ($request->has('name'))
            $user->name = $request->name;

        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::UNVERIFIED_USER;
            $user->verification_token = User::generateVerificationCode();
            $user->email = $request->email;
        }

        if ($request->has('password'))
            $user->password = Hash::make($request->password);

        if ($request->has('admin')) {
            if (!$user->isVerified())
                return $this->errorResponse('Only verified users can modify the admin field', 409);
            $user->admin = $request->admin;
        }

        # if it returns true then this means the user attributes has been changed
        if (!$user->isDirty())
            return $this->errorResponse('You have to specify a different values to update', 422);

        $user->save();
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // DANGER: should add an exception if user does not exist
        $user->delete();
        return $this->showOne($user);
    }
}
