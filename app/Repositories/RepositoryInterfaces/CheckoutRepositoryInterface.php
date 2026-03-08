<?php

namespace App\Repositories\RepositoryInterfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface CheckoutRepositoryInterface
{
    public function getAllCheckout(?User $user = null): Collection;
    public function getUnpaidCheckout(?User $user = null): Collection;
    public function getCheckoutById(int $id): ?Model;
    public function createCheckout(array $data): Model;
    public function updateCheckout(int $id, array $data): Model;
    public function deleteCheckout(int $id): bool;
}
