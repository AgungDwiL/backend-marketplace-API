<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\RepositoryInterfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserRepository extends Repository implements UserRepositoryInterface
{
    public function getAllUser(): Collection
    {
        return User::all();
    }

    public function getUserById(int $id): ?Model
    {
        return User::find($id);
    }

    public function createUser(array $data): Model
    {
        $data['password'] = Hash::make($data['password']);
        $user = new User($data);
        $user->save();
        return $user;
    }

    public function updateUser(int $id, array $data): Model
    {
        $user = User::find($id)->update($data);
        return $user;
    }

    public function deleteUser(int $id): bool
    {
        try {
            User::find($id)->delete();
            return true;
        } catch (Exception $e) {
            Log::error('Can not delete User id ' . $id . ' because ' . $e->getMessage());
            return false;
        }
    }
}
