<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tarjetas; 
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'imagen' => 'nullable|string|max:255',
            'contador_clics' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tarjeta = Tarjetas::create($request->all());
        return response()->json(['tarjeta' => $tarjeta], 201);
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

    public function destroy($id)
    {
        $tarjeta = Tarjetas::find($id);
        if (!$tarjeta) {
            return response()->json(['message' => 'Tarjeta not found'], 404);
        }

        $tarjeta->delete();
        return response()->json(['message' => 'Tarjeta deleted successfully'], 200);
    }
}
