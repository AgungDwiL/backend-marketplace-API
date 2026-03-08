<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCheckoutRequest;
use App\Http\Requests\UpdateCheckoutRequest;
use App\Http\Resources\CheckoutCollection;
use App\Http\Resources\CheckoutResource;
use App\Models\Checkout;
use App\Models\CheckoutDetail;
use App\Repositories\CheckoutRepository;
use App\Repositories\RepositoryInterfaces\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        protected CheckoutRepository $chekcoutRepository,
        protected ProductRepositoryInterface $productRepository
    ) {}

    public function index(): JsonResponse
    {
        $user = Auth::user();
        $checkouts = $this->chekcoutRepository->getAllCheckout($user);

        return (new CheckoutCollection($checkouts))
            ->response()
            ->setStatusCode(200);
    }

    public function details(int $id)
    {
        $checkout = $this->chekcoutRepository->getCheckoutById($id);
        $user = Auth::user();

        if (!$checkout) {
            return response()->json([
                'message' => 'Checkout not found.',
            ], 404);
        } else {
            if ($user->id != $checkout->user_id) {
                return response()->json([
                    'message' => 'Unauthorized.',
                ], 403);
            } else {
                return (new CheckoutResource($checkout))
                    ->response()
                    ->setStatusCode(200);
            }
        }
    }

    public function create(CreateCheckoutRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();
        $checkout = new Checkout([
            'user_id' => $user->id,
            'total_amount' => 0,
        ]);
        $checkout->save();
        $totalAmount = 0;

        foreach ($data['products'] as $row) {
            CheckoutDetail::create([
                'checkout_id' => $checkout->id,
                'product_id' => $row['id'],
            ]);
            $product = $this->productRepository->getProductById($row['id']);
            $totalAmount += $product->price;
        }

        $checkout->update([
            'total_amount' => $totalAmount,
        ]);

        return (new CheckoutResource($checkout->load('details.product')))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateCheckoutRequest $request, int $id)
    {
        $user = Auth::user();
        $checkout = $this->chekcoutRepository->getCheckoutById($id);
        $data = $request->validated();

        if (!$checkout) {
            return response()->json([
                'message' => 'Checkout not found.',
            ], 404);
        } else {
            if ($user->id != $checkout->user_id) {
                return response()->json([
                    'message' => 'Unauthorized.',
                ], 403);
            } else {
                $checkout->details()->delete();
                $totalAmount = 0;
                foreach ($data['products'] as $row) {
                    CheckoutDetail::create([
                        'checkout_id' => $checkout->id,
                        'product_id' => $row['id'],
                    ]);
                    $product = $this->productRepository->getProductById($row['id']);
                    $totalAmount += $product->price;
                }
                $checkout->update([
                    'total_amount' => $totalAmount,
                ]);

                return (new CheckoutResource($checkout->load('details.product')))
                    ->response()
                    ->setStatusCode(200);
            }
        }
    }

    public function delete(int $id)
    {
        $user = Auth::user();
        $checkout = $this->chekcoutRepository->getCheckoutById($id);

        if (!$checkout) {
            return response()->json([
                'message' => 'Checkout not found.',
            ], 404);
        } else {
            if ($user->id != $checkout->user_id) {
                return response()->json([
                    'message' => 'Unauthorized.',
                ], 403);
            } else {
                $checkout->details()->delete();
                $checkout->delete();

                return response()->json([
                    'message' => 'Ok',
                ], 200);
            }
        }
    }

    public function deleteDetail(int $checkout_id, int $detail_id)
    {
        $user = Auth::user();
        $checkout = $this->chekcoutRepository->getCheckoutById($checkout_id);

        if (!$checkout) {
            return response()->json([
                'message' => 'Checkout not found.',
            ], 404);
        } else {
            if ($user->id != $checkout->user_id) {
                return response()->json([
                    'message' => 'Unauthorized.',
                ], 403);
            } else {
                $deleteCheckoutDetail = $this->chekcoutRepository->deleteCheckoutDetail($detail_id);

                if (!$deleteCheckoutDetail) {
                    return response()->json([
                        'message' => 'Checkout Detail not found.',
                    ], 404);
                } else {
                    return response()->json([
                        'message' => 'Ok',
                    ], 200);
                }
            }
        }
    }

}
