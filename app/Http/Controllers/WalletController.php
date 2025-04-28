<?php

namespace App\Http\Controllers;

use App\Constant;
use App\Events\WalletCreated;
use App\Http\Requests\StoreWalletRequest;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WalletController extends Controller
{
    public function store(StoreWalletRequest $request)
    {
        $input = $request->validated();

        /** @var \App\Models\User */
        $user = auth('web')->user();

        // $walletCreated = WalletCreated::fire(
        //     user_id: $user->id,
        //     name: $input['name'],
        // );

        $wallet = Wallet::create([
            'user_id' => $user->id,
            'name' => $input['name'],
        ]);

        // dd($walletCreated);

        // dump($walletCreated, $walletCreated->wallet_id);

        $wallet = Wallet::query()
            ->ofUser($user)
            ->find($wallet->id);

        $user->subscription(config('cashier.product'))->incrementQuantity();

        $request->user()->update([
            'current_wallet_id' => $wallet->id,
        ]);

        return back();
    }

    public function show(Request $request)
    {
        $wallet = $request->user()->currentWallet;

        return Inertia::render('Wallets/Show', [
            'wallet' => $wallet,
        ]);
    }

    public function update(Request $request)
    {
        $input = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:30'],
            'currency' => ['required', 'string', 'in:'.implode(',', array_keys(Constant::AVAILABLE_CURRENCIES()))],
        ]);

        $wallet = $request->user()->currentWallet;

        $wallet->update($input);

        return back();
    }

    public function destroy(Request $request)
    {
        /** @var \App\Models\User */
        $user = $request->user();

        if ($user->wallets()->count() <= 1) {
            return back()->with('error', 'Você não pode deletar sua única carteira.');
        }

        $wallet = $user->currentWallet;

        $wallet->delete();

        $user->update([
            'current_wallet_id' => $user->wallets()->whereNot('id', $wallet->id)->first()->id,
        ]);

        $user->subscription(config('cashier.product'))->decrementQuantity();

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
