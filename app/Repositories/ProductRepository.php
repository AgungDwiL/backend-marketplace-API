<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Vendor;
use App\Repositories\RepositoryInterfaces\ProductRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ProductRepository extends Repository implements ProductRepositoryInterface
{
    public function getAllProduct(): Collection
    {
        return Product::all();
    }

    public function getActiveProduct(): Collection
    {
        $vendor = Vendor::where('is_suspend', 'false')->get();
        return $vendor->product();
    }

    public function getProductById(int $id): ?Model
    {
        return Product::find($id);
    }

    public function createProduct(array $data): Model
    {
        $user = new Product($data);
        return $user;
    }

    public function updateProduct(int $id, array $data): Model
    {
        $user = Product::find($id)->update($data);
        return $user;
    }

    public function deleteProduct(int $id): bool
    {
        try {
            Product::find($id)->delete();
            return true;
        } catch (Exception $e) {
            Log::error('Can not delete Product id ' . $id . ' because ' . $e->getMessage());
            return false;
        }
    }
}
