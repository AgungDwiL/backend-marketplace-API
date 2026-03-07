<?php

namespace App\Repositories\RepositoryInterfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function getAllProduct(): Collection;
    public function getActiveProduct(): Collection;
    public function getProductById(int $id): ?Model;
    public function createProduct(array $data): Model;
    public function updateProduct(int $id, array $data): Model;
    public function deleteProduct(int $id): bool;
}
