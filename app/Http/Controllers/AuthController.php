<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $data["password"] = Hash::make($data["password"]);
        $user = User::query()->create($data);
        $token = $user->createToken("auth");
        return response(["token" => $token->plainTextToken], 200);
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = User::query()->where("email", $data["email"])->first();
        if (Hash::check($data["password"], $user->password)) {
            $user->tokens()->delete();
            $token = $user->createToken("auth");
            return response(["token" => $token->plainTextToken, "roles" => RoleResource::collection($user->roles()->get())], 200);
        }
        return response(["message" => "Invalid password!"], 422);
    }

    public function user()
    {
        return response(["user" => UserResource::make(auth()->user())], 200);
    }

    public function logout()
    {
        $user = auth()->user();
        $user->tokens()->delete();
        return response([], 200);
    }

}
