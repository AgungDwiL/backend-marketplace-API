<?php

namespace App\Http\Controllers;

use App\Http\Resources\CheckoutCollection;
use App\Http\Resources\CheckoutResource;
use App\Repositories\CheckoutRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        protected CheckoutRepository $chekcoutRepository
    ) {}

    public function index(): JsonResponse
    {
        $user = Auth::user();
        $checkouts = $this->chekcoutRepository->getAllCheckout($user);

        return (new CheckoutCollection($checkouts))
            ->response()
            ->setStatusCode(200);
    }
}
