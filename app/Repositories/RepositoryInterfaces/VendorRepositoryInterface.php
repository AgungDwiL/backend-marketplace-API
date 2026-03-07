<?php

namespace App\Repositories\RepositoryInterfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface VendorRepositoryInterface
{
    public function getAllVendor(): Collection;
    public function getActiveVendor(): Collection;
    public function getVendorById(int $id): ?Model;
    public function createVendor(array $data): Model;
    public function updateVendor(int $id, array $data): Model;
    public function deleteVendor(int $id): bool;
}
