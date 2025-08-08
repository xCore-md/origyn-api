<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Discipline;
use App\Models\Language;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'language_code' => 'nullable|string|exists:languages,code',
            'theme' => 'nullable|in:default,dark,light',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $customerRole = Role::customer();
        
        // Get default language or use provided one
        $language = $request->language_code 
            ? Language::findByCode($request->language_code)
            : Language::getDefault();
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_guest' => false,
            'role_id' => $customerRole->id,
            'language_id' => $language?->id,
            'theme' => $request->theme ?? 'default',
            'xp' => 0,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'user' => new UserResource($user->load(['role', 'level', 'language'])),
            'token' => $token,
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => new UserResource($user->load(['role', 'level', 'language'])),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user()->load(['role', 'level', 'language', 'achievements', 'disciplines']);
        
        return response()->json([
            'success' => true,
            'user' => new UserResource($user),
        ]);
    }

    public function convertGuestToRegistered(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!$user->is_guest) {
            return response()->json([
                'success' => false,
                'message' => 'User is not a guest',
            ], 400);
        }

        $customerRole = Role::customer();

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_guest' => false,
            'guest_token' => null,
            'role_id' => $customerRole->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Guest account converted to registered user successfully',
            'user' => new UserResource($user->load(['role', 'level', 'language'])),
        ]);
    }

    public function assignDiscipline(Request $request, Discipline $discipline)
    {
        $user = $request->user();
        
        if ($user->disciplines()->where('discipline_id', $discipline->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Discipline already assigned to user',
            ], 409);
        }

        $user->disciplines()->attach($discipline);

        return response()->json([
            'success' => true,
            'message' => 'Discipline assigned successfully',
            'discipline' => $discipline->only(['id', 'name', 'icon']),
        ]);
    }

    public function removeDiscipline(Request $request, Discipline $discipline)
    {
        $user = $request->user();
        
        if (!$user->disciplines()->where('discipline_id', $discipline->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Discipline not assigned to user',
            ], 404);
        }

        $user->disciplines()->detach($discipline);

        return response()->json([
            'success' => true,
            'message' => 'Discipline removed successfully',
        ]);
    }

    public function updateSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'theme' => 'nullable|in:default,dark,light',
            'language_id' => 'nullable|exists:languages,id',
            'discipline_ids' => 'nullable|array',
            'discipline_ids.*' => 'exists:disciplines,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $updateData = [];

        if ($request->has('theme')) {
            $updateData['theme'] = $request->theme;
        }

        if ($request->has('language_id')) {
            $updateData['language_id'] = $request->language_id;
        }

        if (!empty($updateData)) {
            $user->update($updateData);
        }

        if ($request->has('discipline_ids')) {
            $user->disciplines()->sync($request->discipline_ids);
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully',
            'user' => new UserResource($user->load(['role', 'level', 'language', 'disciplines'])),
        ]);
    }
}
