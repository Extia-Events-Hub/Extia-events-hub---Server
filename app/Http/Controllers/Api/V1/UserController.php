<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
// use Laravel\Passport\RefreshToken;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(10);
        return response()->json([
            'status' => true,
            'data' => $users
        ], 200);
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'nullable',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'role' => $validatedData['role'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $accessToken = $user->createToken('API Token')->accessToken;

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'access_token' => $accessToken,
            'user' => $user,
        ], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!auth()->attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Invalid credentials'],
            ]);
        }

        $accessToken = auth()->user()->createToken('API Token')->accessToken;

        

        return response()->json([
            'status' => true,
            'message' => 'User Logged In successfully',
            'access_token' => $accessToken,
        ], 200);
    }

    // public function login(Request $request)
    // {
    //     $credentials = $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);
    //     if (!auth()->attempt($credentials)) {
    //         throw ValidationException::withMessages([
    //             'email' => ['Invalid credentials'],
    //         ]);
    //     }
    //     $accessToken = auth()->user()->createToken('API Token')->accessToken;
    //     $refreshToken = $accessToken->refreshToken;
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'User Logged In successfully',
    //         'access_token' => $accessToken,
    //         'refresh_token' => $refreshToken->refresh_token,
    //     ], 200);
    // }


    //

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'message' => 'Logged Out Successfully',
        ]);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response($user, 201);
    }


    public function getRefreshToken(Request $request)
    {
        $refreshToken = $request->user()->tokens()->where('name', 'API Token')->first();
        if (!$refreshToken) {
            return response()->json([
                'status' => false,
                'message' => 'Refresh token not found',
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Refresh token obtained successfully',
            'refresh_token' => $refreshToken->id,
        ], 200);
    }
}
