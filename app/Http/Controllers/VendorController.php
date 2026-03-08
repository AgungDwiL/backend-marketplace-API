<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Http\Resources\VendorCollection;
use App\Http\Resources\VendorResource;
use App\Repositories\RepositoryInterfaces\VendorRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function __construct(
        protected VendorRepositoryInterface $vendorRepository
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $vendors = $this->vendorRepository->getActiveVendor();

        return (new VendorCollection($vendors))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CreateVendorRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $vendor = $this->vendorRepository->createVendor($data);

        return (new VendorResource($vendor))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function detail(int $id): JsonResponse
    {
        $vendor = $this->vendorRepository->getVendorById($id);
        $vendor->load('products');

        if (!$vendor) {
            return response()->json([
                'message' => 'Vendor not found.',
            ], 404);
        } else {
            return (new VendorResource($vendor))
                ->response()
                ->setStatusCode(200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVendorRequest $request, int $id)
    {
        $data = $request->validated();
        $vendor = $this->vendorRepository->getVendorById($id);

        if (!$vendor) {
            return response()->json([
                'message' => 'Vendor not found.',
            ], 404);
        } else {
            if ($vendor->user_id != Auth::user()->id) {
                return response()->json([
                    'message' => 'Unauthorized.',
                ], 403);
            } else {
                $vendor->update($data);
                return (new VendorResource($vendor))
                    ->response()
                    ->setStatusCode(200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(int $id)
    {
        $vendor = $this->vendorRepository->getVendorById($id);
        if ($vendor->user_id != Auth::user()->id) {
            return response()->json([
                'message' => 'Unauthorized.',
            ]);
        } else {
            $vendor->update([
                'is_suspend' => 'true',
            ]);

            return response()->json([
                'message' => 'Ok',
            ]);
        }
    }
}
