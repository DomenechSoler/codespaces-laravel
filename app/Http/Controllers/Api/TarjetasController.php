<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarjetas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TarjetasController extends Controller
{
    public function index()
    {
        $tarjetas = Tarjetas::all();
        return response()->json(['tarjetas' => $tarjetas], 200);
    }

    public function show($id)
    {
        $tarjeta = Tarjetas::find($id);
        if ($tarjeta) {
            return response()->json(['tarjeta' => $tarjeta], 200);
        } else {
            return response()->json(['message' => 'Tarjeta not found'], 404);
        }
    }

public function store(Request $request)
{
    $request->validate([
        'nombre' => 'required|string|max:100',
        'imagen' => 'required|url',
        'category_id' => 'nullable|exists:categories,id',
    ]);

    $tarjeta = Tarjetas::create([
        'nombre' => $request->nombre,
        'imagen' => $request->imagen,
        'category_id' => $request->category_id,
        'user_id' => Auth::id(), // 🔑 afegim l'usuari que l'ha creat
    ]);

    return response()->json([
        'message' => 'Targeta creada',
        'data' => $tarjeta
    ], 201);
}


    public function update(Request $request, $id)
    {
        $tarjeta = Tarjetas::find($id);
        if (!$tarjeta) {
            return response()->json(['message' => 'Tarjeta not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nombre' => 'sometimes|required|string|max:255',
            'imagen' => 'sometimes|nullable|string|max:255',
            'contador_clics' => 'sometimes|nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tarjeta->update($request->all());
        return response()->json(['tarjeta' => $tarjeta], 200);
    }

    // public function destroy($id)
    // {
    //     $tarjeta = Tarjetas::find($id);
    //     if (!$tarjeta) {
    //         return response()->json(['message' => 'Tarjeta not found'], 404);
    //     }

    //     $tarjeta->delete();
    //     return response()->json(['message' => 'Tarjeta deleted successfully'], 200);
    // }
    public function getByCategory($categoryId)
{
    $tarjeta = Tarjetas::where('category_id', $categoryId)->get();

    return response()->json($tarjeta);
}

public function myCards()
{
    $tarjeta = Tarjetas::where('user_id', Auth::id())->get();

    return response()->json([
        'message' => 'Les teves targetes',
        'data' => $tarjeta
    ]);
}

public function destroy(Tarjetas $tarjeta)
{
    $user = Auth::user();
    if ($tarjeta->user_id !== $user->id && $user->role !== 'admin') {
        return response()->json(['error' => 'No autoritzat'], 403);
    }

    $tarjeta->delete();
    return response()->json(['message' => 'Targeta eliminada']);
}


}
