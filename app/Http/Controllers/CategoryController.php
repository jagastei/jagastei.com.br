<?php

namespace App\Http\Controllers;

use App\Events\CategoryCreated;
use App\Http\Requests\StoreCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::query()
            ->ofUser(auth('web')->user())
            ->get();

        return Inertia::render('Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $input = $request->validated();

        $userId = auth('web')->id();

        CategoryCreated::fire(
            user_id: $userId,
            name: $input['name'],
            color: $input['color'],
        );

        return back();
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back();
    }
}
