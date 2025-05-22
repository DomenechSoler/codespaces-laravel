<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pets;

class PetsController extends Controller
{
    // Veure les pròpies mascotes
    public function index(Request $request)
    {
        $userId = Auth::id();
        $pets = Pets::where('user_id', $userId)->get();

        return response()->json([
            'message' => 'Tus mascotas',
            'data' => $pets
        ], 200);

    }



     // Crear una mascota
    public function store(Request $request)
    {
        $pet = $request->user()->pets()->create($request->only(['nombre', 'imagen']));
        return response()->json([
            'message' => 'Mascota creada',
            'data' => $pet
        ], 201);
    }

    // Editar completament una mascota seva
    public function update(Request $request, $id)
    {
        $pet = $request->user()->pets()->findOrFail($id);
        $pet->update($request->only(['nombre', 'imagen']));
        return response()->json([
            'message' => 'Mascota actualizada',
            'data' => $pet
        ]);
    }

    // Editar parcialment una mascota seva
    public function partialUpdate(Request $request, $id)
    {
        $pet = $request->user()->pets()->findOrFail($id);
        $pet->fill($request->only(['nombre', 'imagen']))->save();
        return response()->json([
            'message' => 'Mascota actualizada parcialmente',
            'data' => $pet
        ]);
    }

    // Eliminar una mascota seva
    public function destroy(Request $request, $id)
    {
        $pet = $request->user()->pets()->findOrFail($id);
        $pet->delete();
        return response()->json([
            'message' => 'Mascota eliminada',
            'data' => $pet
        ]);
    }
}
