<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRequest;
use App\Http\Resources\CheckoutCollection;
use App\Http\Resources\TransactionResource;
use App\Repositories\RepositoryInterfaces\TransactionRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionRepositoryInterface $transactionRepository
    ) {}

    public function index()
    {
        $user = Auth::user();
        $transactions = $this->transactionRepository->getAllTransaction($user);
        $transactions->load(['buyer', 'vendor', 'checkout']);

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
        $user = Auth::user();

    }

    public function delete(int $id)
    {
        # code...
    }

}
