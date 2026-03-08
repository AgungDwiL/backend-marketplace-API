<?php

namespace App\Services;

use App\Repositories\RepositoryInterfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $userRepossitory
    ) {}

    public function login(string $identifier, string $password): ?Model
    {
        $field = filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($field === 'email') {
            $user = $this->userRepossitory->getUserByEmail($identifier);
        } else {
            $user = $this->userRepossitory->getUserByUsername($identifier);
        }

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        } else {
            return $user;
        }
    }
}
