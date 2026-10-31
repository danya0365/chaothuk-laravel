<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\WalletTransactionCollection;
use App\Models\WalletTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * The caller's own wallet.
 *
 * Read-only on purpose: money moves where the work happens — a promotion
 * charge, a refund, an admin adjustment — and every one of those already
 * files a ledger row with its balance_after. This controller surfaces the
 * ledger; it never invents a movement.
 */
class WalletController extends Controller
{
    /**
     * The current balance and the wallet's state.
     */
    public function show(Request $request): JsonResponse
    {
        $wallet = $request->user()->wallet()->first();

        return response()->json([
            'data' => [
                'balance' => $wallet ? (float) $wallet->balance : 0.0,
                'status' => $wallet?->status ?? 'active',
            ],
        ]);
    }

    /**
     * The ledger, newest first.
     */
    public function transactions(Request $request): WalletTransactionCollection
    {
        $wallet = $request->user()->wallet;

        if (! $wallet) {
            return WalletTransactionCollection::make(collect());
        }

        $transactions = WalletTransaction::query()
            ->where('user_wallet_id', $wallet->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate(min(max($request->integer('per_page', 15), 1), 50))
            ->withQueryString();

        return WalletTransactionCollection::make($transactions);
    }
}
