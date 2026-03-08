<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\RepositoryInterfaces\UserRepositoryInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {}

    public function login()
    {
        # code...
    }

    public function register(RegisterRequest $request)
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
