<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Events\CategoryCreated;
use App\Http\Requests\StoreCategoryRequest;

final class CategoryController extends Controller
{
    public function store(StoreCategoryRequest $request)
    {
        $input = $request->validated();

        $walletId = auth('web')->user()->currentWallet->id;

        $category = CategoryCreated::commit(
            wallet_id: $walletId,
            name: $input['name'],
            color: $input['color'],
            type: $input['type'],
        );

        return response()->json($category);
    }
}
