<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    // 1. Llistar les partides de l'usuari autenticat
    public function index()
    {
        $userId = Auth::id();
        $games = Game::where('user_id', $userId)->get();

        return response()->json([
            'message' => 'Llistat de partides',
            'data' => $games
        ], 200);
    }

    // 2. Crear una nova partida buida
    public function store()
    {
        $game = Game::create([
            'user_id' => Auth::id(),
            'clicks' => 0,
            'points' => 0,
            'duration' => null
        ]);

        return response()->json([
            'message' => 'Partida creada',
            'data' => $game
        ], 201);
    }

    // 3. Finalitzar una partida (només el propietari)
    public function update(Request $request, Game $game)
    {
        if ($game->user_id !== Auth::id()) {
            return response()->json(['error' => 'No autoritzat'], 403);
        }

        $validated = $request->validate([
            'clicks' => 'required|integer|min:0',
            'points' => 'required|integer|min:0',
            'duration' => 'required|integer|min:1',
        ]);

        $game->update($validated);

        return response()->json([
            'message' => 'Partida finalitzada',
            'data' => $game
        ], 200);
    }

    // 4. Eliminar una partida (propietari o admin)
    public function destroy(Game $game)
    {
        $user = Auth::user();

        if ($user->id !== $game->user_id && $user->role !== 'admin') {
            return response()->json(['error' => 'No autoritzat'], 403);
        }

        $game->delete();

        return response()->json(['message' => 'Partida eliminada'], 200);
    }

    // 5. Ranking (top 5 jugadors)
    public function ranking()
    {
        $ranking = Game::select('user_id')
            ->selectRaw('MIN(duration) as best_time')
            ->selectRaw('MIN(clicks) as min_clicks')
            ->selectRaw('MAX(points) as max_points')
            ->groupBy('user_id')
            ->orderBy('best_time')
            ->orderBy('min_clicks')
            ->with('user')
            ->take(5)
            ->get();

        return response()->json([
            'message' => 'Top 5 jugadors',
            'data' => $ranking
        ], 200);
    }

    // 6. Llistar partides d'un usuari concret (només admin)
    public function getGamesByUserId($id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Només per admins'], 403);
        }

        $games = Game::where('user_id', $id)->get();

        return response()->json([
            'message' => "Partides de l’usuari $id",
            'data' => $games
        ]);
    }

        // 7. Listar todas las partidas (solo admin)
    public function adminIndex()
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Només per admins'], 403);
        }
        $games = Game::with('user')->get();
        return response()->json([
            'message' => 'Listado de todas las partidas',
            'data' => $games
        ], 200);
    }

    // 8. Mostrar una partida concreta (solo admin)
    public function adminShow(Game $game)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Només per admins'], 403);
        }
        $game->load('user');
        return response()->json([
            'message' => 'Partida encontrada',
            'data' => $game
        ], 200);
    }

    // 9. Crear una partida (solo admin)
    public function adminStore(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Només per admins'], 403);
        }
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'clicks' => 'required|integer|min:0',
            'points' => 'required|integer|min:0',
            'duration' => 'required|integer|min:1',
        ]);
        $game = Game::create($validated);
        return response()->json([
            'message' => 'Partida creada por admin',
            'data' => $game
        ], 201);
    }

    // 10. Actualizar una partida (solo admin)
    public function adminUpdate(Request $request, Game $game)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Només per admins'], 403);
        }
        $validated = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'clicks' => 'sometimes|integer|min:0',
            'points' => 'sometimes|integer|min:0',
            'duration' => 'sometimes|integer|min:1',
        ]);
        $game->update($validated);
        return response()->json([
            'message' => 'Partida actualizada por admin',
            'data' => $game
        ], 200);
    }

    // 11. Eliminar una partida (solo admin)
    public function adminDestroy(Game $game)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            return response()->json(['error' => 'Només per admins'], 403);
        }
        $game->delete();
        return response()->json(['message' => 'Partida eliminada por admin'], 200);
    }


}
