<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\CategoryCreated;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Inertia\Inertia;

final class CategoryController extends Controller
{
    public function index()
    {
        $inCategories = Category::query()
            ->ofWallet(auth('web')->user()->currentWallet)
            ->in()
            ->withCount([
                'transactions',
            ])
            ->withSum([
                'transactions',
            ], 'value')
            ->get()
            ->map(function ($category) {
                $category->transactions_sum_value /= 100;

                return $category;
            });

        $outCategories = Category::query()
            ->ofWallet(auth('web')->user()->currentWallet)
            ->out()
            ->withCount([
                'transactions',
            ])
            ->withSum([
                'transactions',
            ], 'value')
            ->get()
            ->map(function ($category) {
                $category->transactions_sum_value /= 100;

                return $category;
            });

        return Inertia::render('Categories/Index', [
            'inCategories' => $inCategories,
            'outCategories' => $outCategories,
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $input = $request->validated();

        $walletId = auth('web')->user()->currentWallet->id;

        $category = CategoryCreated::fire(
            wallet_id: $walletId,
            name: $input['name'],
            color: $input['color'],
            type: $input['type'],
        );

        return back();
    }

    public function show(Category $category)
    {
        return Inertia::render('Categories/Show', [
            'category' => $category,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $input = $request->validated();

        $category->update($input);

        return back();
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back();
    }
}
