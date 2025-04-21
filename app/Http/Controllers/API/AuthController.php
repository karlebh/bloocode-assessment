<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ResponseTrait;

    public function register(CreateUserRequest $request)
    {
        try {
            $requestData = $request->validated();

            $user = User::create([
                'name' => $requestData['name'],
                'email' => $requestData['email'],
                'password' => Hash::make($requestData['password']),
            ]);

            if (! $user) {
                $this->badRequestResponse('Could not create user');
            }

            event(new Registered($user));

            Auth::login($user);

            $token = $user->createToken('API TOKEN')->plainTextToken;
            $token = $this->cleanToken($token);

            return $this->successResponse('User created successfully', ['user' => $user]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Sever error', $exception);
        }
    }

    public function login(UserLoginRequest $request)
    {
        try {
            $requestData = $request->validated();

            $user = User::where('email', $requestData['email'])
                ->first();

            if (! $user || ! Hash::check($requestData['password'], $user->password)) {
                return $this->notFoundResponse('This user does not exist. please register');
            }

            if (! Auth::attempt(['email' => $user->email, 'password' => $requestData['password']])) {
                return $this->badRequestResponse('Invalid credentials', 403);
            }

            $token =
                $this->cleanToken(
                    $user->createToken($user->email)->plainTextToken
                );

            return $this->successResponse('Login successful', [
                'token' => $token,
                'user' => $user
            ]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Sever error', $exception);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            $user->tokens()->delete();

            return $this->successResponse('Logout succesful');
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Sever error', $exception);
        }
    }

    private function cleanToken($token)
    {
        return (explode('|', $token))[1];
    }
}
