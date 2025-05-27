<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
   public function index()
{
    return Category::with('tarjetas')->get(); // opcional
}

public function store(Request $request)
{
    $request->validate(['name' => 'required|string|max:100']);

    return Category::create($request->all());
}

public function update(Request $request, Category $category)
{
    $request->validate(['name' => 'required|string|max:100']);

    $category->update($request->all());
    return $category;
}

public function destroy(Category $category)
{
    $category->delete();
    return response()->json(['message' => 'Categoria eliminada']);
}

}
