<?php

namespace App\Repositories;

use App\Models\Vendor;
use App\Repositories\RepositoryInterfaces\VendorRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class VendorRepository extends Repository implements VendorRepositoryInterface
{
    public function getAllVendor(): Collection
    {
        return Vendor::all();
    }

    public function getActiveVendor(): Collection
    {
        return Vendor::where('is_suspend', 'false')->all();
    }

    public function getVendorById(int $id): ?Model
    {
        return Vendor::find($id);
    }

    public function createVendor(array $data): Model
    {
        $vendor = new Vendor($data);
        $vendor->save();
        return $vendor;
    }

    public function updateVendor(int $id, array $data): Model
    {
        $vendor = Vendor::find($id)->update($data);
        return $vendor;
    }

    public function deleteVendor(int $id): bool
    {
        try {
            Vendor::find($id)->delete();
            return true;
        } catch (Exception $e) {
            Log::error('Can not delete Vendor id ' . $id . ' because ' . $e->getMessage());
            return false;
        }
    }
}
