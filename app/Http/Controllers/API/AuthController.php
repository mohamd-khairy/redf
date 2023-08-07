<?php

namespace App\Http\Controllers\API;

use App\Enums\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validate->fails()) {
            return responseFail($validate->messages()->first());
        }

        // ldap
        if (
            !auth()->attempt($request->only('email', 'password')) &&
            !auth()->attempt(['cn' => $request->get('email'), 'password' => $request->get('password')]) &&
            !auth()->attempt(['samaccountname' => $request->get('email'), 'password' => $request->get('password')]) &&
            !auth()->attempt(['uid' => $request->get('email'), 'password' => $request->get('password')])
        ) {
            return responseFail('Email & Password does not match with our record.');
        }


        $user = User::with([
            'roles',
            'roles.permissions:name',
        ])->where('email', $request->email)->first();

        if ($user->status == StatusEnum::notactive) {
            return responseFail('This account is not active to login');
        }

        $user->update(['last_login' => now()]);

        return responseSuccess([
            'token' => $user->createToken("API TOKEN")->plainTextToken,
            'user' => new UserResource($user),
        ], 'User Logged In Successfully');
    }

    public function user()
    {
        $user = User::with([
            'roles',
            'roles.permissions:name',
        ])->find(auth()->id());

        return responseSuccess(new UserResource($user), 'User Logged In Successfully');
    }

    public function logout()
    {
        $user = User::find(auth()->id());
        $user->tokens()->where('id', auth()->user()->currentAccessToken()->id)->delete();
        return responseSuccess([], 'User Logged Out Successfully');
    }
}
