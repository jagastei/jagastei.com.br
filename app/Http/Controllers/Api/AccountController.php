<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\AccountCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountRequest;

final class AccountController extends Controller
{
    public function store(StoreAccountRequest $request)
    {
        $input = $request->validated();

        $walletId = auth('web')->user()->currentWallet->id;

        $account = AccountCreated::commit(
            wallet_id: $walletId,
            bank_id: $input['bank'],
            name: $input['name'],
            initial_balance: $input['initial_balance'],
        );

        $account->load('bank');

        return response()->json($account);
    }
}
