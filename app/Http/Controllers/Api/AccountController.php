<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\AccountCreated;
use App\Events\AccountDeleted;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use App\Models\Bank;
use App\States\AccountState;
use Carbon\Carbon;
use Inertia\Inertia;

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

        return response()->json($account);
    }
}
