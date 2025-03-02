<?php

namespace App\Http\Controllers;

use App\Events\WalletCreated;
use App\Http\Requests\StoreWalletRequest;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function store(StoreWalletRequest $request)
    {
        $input = $request->validated();

        $userId = auth('web')->id();

        WalletCreated::fire(
            user_id: $userId,
            name: $input['name'],
        );

        return back();
    }

    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        return redirect()->route('dashboard');
    }

    public function switch(Request $request)
    {
        $input = $request->validate([
            'wallet_id' => ['required', 'uuid'],
        ]);

        $wallet = Wallet::query()
            ->ofUser($request->user())
            ->findOrFail($input['wallet_id']);

        $request->user()->update([
            'current_wallet_id' => $wallet->id,
        ]);

        return back();
    }
}
