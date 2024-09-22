<?php

namespace App\Http\Controllers;

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

    public function store()
    {
        return back();
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back();
    }
}
