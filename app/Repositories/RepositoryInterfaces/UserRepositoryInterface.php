<?php

namespace App\Repositories\RepositoryInterfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function getAllUser(): Collection;
    public function getUserByEmail(string $email): ?Model;
    public function getUserByUsername(string $username): ?Model;
    public function getUserById(int $id): ?Model;
    public function createUser(array $data): Model;
    public function updateUser(int $id, array $data): Model;
    public function deleteUser(int $id): bool;
}
