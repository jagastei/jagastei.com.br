<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function switch(Request $request)
    {
        $input = $request->validate([
            'wallet_id' => ['required', 'exists:wallets,id'],
        ]);

        $wallet = Wallet::query()
            ->ofUser($request->user())
            ->findOrFail($input['wallet_id']);

        $request->user()->update([
            'current_wallet_id' => $wallet->id,
        ]);

        return response()->noContent();
    }
}
