<?php

namespace App\Repositories;

use App\Models\Checkout;
use App\Repositories\RepositoryInterfaces\CheckoutRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class CheckoutRepository extends Repository implements CheckoutRepositoryInterface
{
    public function getAllCheckout(): Collection
    {
        return Checkout::all();
    }

    public function getUnpaidCheckout(): Collection
    {
        return Checkout::doesntHave('transaction')->get();
    }

    public function getCheckoutById(int $id): ?Model
    {
        return Checkout::find($id);
    }

    public function createCheckout(array $data): Model
    {
        $user = new Checkout($data);
        return $user;
    }

    public function updateCheckout(int $id, array $data): Model
    {
        $user = Checkout::find($id)->update($data);
        return $user;
    }

    public function deleteCheckout(int $id): bool
    {
        try {
            Checkout::find($id)->delete();
            return true;
        } catch (Exception $e) {
            Log::error('Can not delete Checkout id ' . $id . ' because ' . $e->getMessage());
            return false;
        }
    }
}
