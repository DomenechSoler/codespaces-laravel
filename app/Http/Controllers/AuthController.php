<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the request with validator library
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'role' => 'required|string|max:100|in:admin,user',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:5|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        // Create the user
        $user = User::create([
            'name' => $request->get('name'),
            'role' => $request->get('role'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
        ]);
        // Return the user
        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }


    //función para login
    public function login(Request $request)
    {
        // Validate the request with validator library
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:5',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        $credentials = $request->only(['email', 'password']);

        // Validate the credentials
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }
            return response()->json([
                'message' => 'User logged in successfully',
                'token' => $token,
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'error'   => 'Could not create token',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    //getuser
    public function getUser()
    {
        $user = Auth::user();
        return response()->json([
            'message' => 'User retrieved successfully',
            'data' => $user,
        ], 200);
    }

    //logout

    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response()->json([
                'message' => 'User logged out successfully',
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Could not log out user',
            ], 500);
        }
    }
    public function getUsers()
{
    return response()->json([
        'message' => 'Users retrieved successfully',
        'data' => User::all(),
    ], 200);
}

// Mostrar usuario por id (admin o el propio usuario)
public function getUserById($id)
{
    $user = Auth::user();
    if ($user->role === 'admin' || $user->id == $id) {
        $found = User::find($id);
        if (!$found) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        return response()->json(['data' => $found], 200);
    }
    return response()->json(['message' => 'No autorizado'], 403);
}

// Actualizar usuario (admin o el propio usuario)
public function updateUser(Request $request, $id)
{
    $user = Auth::user();
    if ($user->role === 'admin' || $user->id == $id) {
        $found = User::find($id);
        if (!$found) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
        $data = $request->only(['name', 'email', 'password', 'role']);
        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }
        // Solo admin puede cambiar el rol
        if ($user->role !== 'admin') {
            unset($data['role']);
        }
        $found->update($data);
        return response()->json(['message' => 'Usuario actualizado', 'data' => $found], 200);
    }
    return response()->json(['message' => 'No autorizado'], 403);
}

// Eliminar usuario (solo admin)
public function deleteUser($id)
{
    $user = Auth::user();
    if ($user->role !== 'admin') {
        return response()->json(['message' => 'No autorizado'], 403);
    }
    $found = User::find($id);
    if (!$found) {
        return response()->json(['message' => 'Usuario no encontrado'], 404);
    }
    $found->delete();
    return response()->json(['message' => 'Usuario eliminado'], 200);
}
}
