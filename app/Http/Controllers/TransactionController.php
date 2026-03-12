<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRequest;
use App\Http\Resources\CheckoutCollection;
use App\Http\Resources\CheckoutResource;
use App\Http\Resources\TransactionCollection;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\RepositoryInterfaces\CheckoutRepositoryInterface;
use App\Repositories\RepositoryInterfaces\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository,
        protected CheckoutRepositoryInterface $checkoutRepository
    ) {}

    public function index()
    {
        $user = Auth::user();
        $transactions = $this->transactionRepository->getAllTransaction($user);
        $transactions->load(['buyer', 'vendor', 'checkoutDetail']);

        return (new CheckoutCollection($transactions))
            ->response()
            ->setStatusCode(200);
    }

    public function detail(int $id)
    {
        $transaction = $this->transactionRepository->getTransactionById($id);
        $user = Auth::user();

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found.',
            ], 404);
        } else {
            if ($user->id != $transaction->user_id) {
                return response()->json([
                    'message' => 'Unauthorized.',
                ], 403);
            } else {
                $transaction->load(['buyer', 'vendor', 'checkout.details.product']);
                return (new TransactionResource($transaction))
                    ->response()
                    ->setStatusCode(200);
            }
        }
    }

    public function create(CreateTransactionRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();
        $checkout = $this->checkoutRepository->getCheckoutById($data['checkout_id']);
        $checkout->load('details', 'details.product');

        $transactions = [];

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
                foreach ($checkout->details as $data) {
                    $transaction = new Transaction([
                        'buyer_id' => $user->id,
                        'vendor_id' => $checkout->details()->product()->vendor_id,
                        'checkout_detail_id' => $checkout->details()->id,
                        'transaction_time' => now(),
                    ]);
                    $transaction->save();

                    $user->balance = $user->balance - $data->product()->price;
                    $vendor = User::find($data->product()->vendor_id);
                    $vendor->balance = $vendor->balance + $data->product()->price;

                    $transactions[] = $transaction;
                }

                $checkout->load(['details', 'details.product']);
                return response()->json([
                    'checkout' => new CheckoutResource($checkout),
                    'transactions' => new TransactionCollection($transactions),
                ], 201);
            }
        }
    }

    public function delete(int $id)
    {
        $user = Auth::user();
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found.',
            ], 404);
        } else {
            if ($user->id != $transaction->vendor_id) {
                return response()->json([
                    'message' => 'Unauthorized.',
                ], 403);
            } else {
                $buyer = User::find($transaction->buyer_id);
                $buyer->balance = $buyer->balance + $transaction->transactionDetail()->product()->price;
                $user->balance = $user->balance - $transaction->transactionDetail()->product()->price;

                return response()->json([
                    'ok',
                ], 200);
            }
        }
    }
}
