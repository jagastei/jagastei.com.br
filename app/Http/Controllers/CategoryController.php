<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\CategoryCreated;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Inertia\Inertia;

final class CategoryController extends Controller
{
    // public function index()
    // {
    //     $categories = Category::query()
    //         ->ofWallet(auth('web')->user()->currentWallet)
    //         ->get();

    //     return Inertia::render('Categories/Index', [
    //         'categories' => $categories,
    //     ]);
    // }

    public function store(StoreCategoryRequest $request)
    {
        $input = $request->validated();

        $walletId = auth('web')->user()->currentWallet->id;

        CategoryCreated::fire(
            wallet_id: $walletId,
            name: $input['name'],
            color: $input['color'],
            type: $input['type'],
        );

        return back();
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back();
    }
}
