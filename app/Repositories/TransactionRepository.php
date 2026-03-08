<?php

namespace App\Repositories;

use App\Models\Transaction;
use App\Models\User;
use Exception;
use App\Repositories\RepositoryInterfaces\TransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TransactionRepository extends Repository implements TransactionRepositoryInterface
{
    public function getAllTransaction(?User $user = null): Collection
    {
        if ($user) {
            return Transaction::where('user_id', $user->id)->get();
        } else {
            return Transaction::all();
        }
    }

    public function getTransactionById(int $id): ?Model
    {
        return Transaction::find($id);
    }

    public function createTransaction(array $data): Model
    {
        $user = new Transaction($data);
        return $user;
    }

    public function updateTransaction(int $id, array $data): Model
    {
        $user = Transaction::find($id)->update($data);
        return $user;
    }

    public function deleteTransaction(int $id): bool
    {
        try {
            Transaction::find($id)->delete();
            return true;
        } catch (Exception $e) {
            Log::error('Can not delete Transaction id ' . $id . ' because ' . $e->getMessage());
            return false;
        }
    }
}
