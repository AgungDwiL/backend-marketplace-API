<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Repositories\RepositoryInterfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected AuthService $auth
    ) {}

    public function login(LoginRequest $request)
    {
        $data = $request->validated();
        $user = $this->auth->login($data['identifier'], $data['password']);

        if ($user) {
            $user->tokens()->delete();
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(
                [
                    'user' =>  new UserResource($user),
                    'auth' => [
                        'token' => $token,
                    ],
                ],
                200
            );
        } else {
            return response()->json([
                'message' => 'Invalid credentials.',
            ], 404);
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $user = $this->userRepository->createUser($data);
        return (new UserResource($user))
                    ->response()
                    ->setStatusCode(201);
    }

    public function logout()
    {
        # code...
    }

    public function update()
    {
        # code...
    }
}
