<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VendorResource;
use App\Repositories\RepositoryInterfaces\ProductRepositoryInterface;
use App\Repositories\RepositoryInterfaces\VendorRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function __construct(
        protected ProductRepositoryInterface $productRepository,
        protected VendorRepositoryInterface $vendorRepository
    ) {}

    public function index(): JsonResponse
    {
        $products = $this->productRepository->getActiveProduct();
        return (new ProductCollection($products))
            ->response()
            ->setStatusCode(200);
    }

    public function indexByVendor(int $vendor_id): JsonResponse
    {
        $vendor = $this->vendorRepository->getVendorById($vendor_id);
        if (!$vendor) {
            return response()->json([
                'message' => 'Vendor not found.',
            ], 404);
        } else {
            $vendor->load('products');
            return (new VendorResource($vendor))
                ->response()
                ->setStatusCode(200);
        }
    }

    public function detail(int $id): JsonResponse
    {
        $product = $this->productRepository->getProductById($id);
        if (!$product) {
            return response()->json([
                'message' => 'Vendor not found.',
            ], 404);
        } else {
            return (new ProductResource($product))
                ->response()
                ->setStatusCode(200);
        }
    }

    public function create(CreateProductRequest $request)
    {
        $data = $request->validated();
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return response()->json([
                'message' => 'Unauthorized, create vendor first.',
            ], 403);
        } else {
            $data['vendor_id'] = $vendor->id;
            $product = $this->productRepository->createProduct($data);
            return (new ProductResource($product))
                ->response()
                ->setStatusCode(201);
        }
    }

    public function update(UpdateProductRequest $request, int $id): JsonResponse
    {
        $data = $request->validated();
        $vendor = Auth::user()->vendor;
        $product = $this->productRepository->getProductById($id);

        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        } else {
            if (!$vendor) {
                return response()->json([
                    'message' => 'Unauthorized, create vendor first.',
                ], 403);
            } elseif ($vendor->id != $product->vendor_id) {
                return response()->json([
                    'message' => 'Unauthorized.',
                ], 403);
            } else {
                $product->update($data);
                return (new ProductResource($product))
                    ->response()
                    ->setStatusCode(200);
            }
        }
    }

    public function delete(int $id)
    {
        $vendor = Auth::user()->vendor;
        $product = $this->productRepository->getProductById($id);


        if (!$product) {
            return response()->json([
                'message' => 'Product not found.',
            ], 404);
        } else {
            if (!$vendor) {
                return response()->json([
                    'message' => 'Unauthorized, create vendor first.',
                ], 403);
            } elseif ($vendor->id != $product->vendor_id) {
                return response()->json([
                    'message' => 'Unauthorized.',
                ], 403);
            } else {
                $product->delete();
                return response()->json([
                    'messate' => 'Ok',
                ], 200);
            }
        }
    }
}
