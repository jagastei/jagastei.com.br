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

        $user = auth('web')->user();

        $walletCreated = WalletCreated::fire(
            user_id: $user->id,
            name: $input['name'],
        );

        // dump($walletCreated, $walletCreated->wallet_id);

        // $wallet = Wallet::query()
        //     // ->ofUser($user)
        //     ->find($walletCreated->wallet_id);

        // dd($wallet);

        // $user->subscription('default')->incrementQuantity();

        return back();
    }

    public function destroy(Wallet $wallet)
    {
        $wallet->delete();

        // $user->subscription('default')->decrementQuantity();

        return redirect()->route('dashboard');
    }

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

        return back();
    }
}
