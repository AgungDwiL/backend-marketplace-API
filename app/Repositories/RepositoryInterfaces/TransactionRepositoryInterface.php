<?php

namespace App\Repositories\RepositoryInterfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface TransactionRepositoryInterface
{
    public function getAllTransaction(?User $user = null): Collection;
    public function getTransactionById(int $id): ?Model;
    public function createTransaction(array $data): Model;
    public function updateTransaction(int $id, array $data): Model;
    public function deleteTransaction(int $id): bool;
}
