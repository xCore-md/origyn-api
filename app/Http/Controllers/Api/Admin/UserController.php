<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->has('role')) {
            $query->whereHas('role', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if ($request->has('is_guest')) {
            $query->where('is_guest', $request->boolean('is_guest'));
        }

        $users = $query->paginate(15);

        return response()->json([
            'success' => true,
            'users' => $users,
        ]);
    }

    public function show(User $user)
    {
        $user->load('role');

        return response()->json([
            'success' => true,
            'user' => $user,
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|exists:roles,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update(['role_id' => $request->role_id]);
        $user->load('role');

        return response()->json([
            'success' => true,
            'message' => 'User role updated successfully',
            'user' => $user,
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete admin users',
            ], 403);
        }

        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully',
        ]);
    }

    public function roles()
    {
        $roles = Role::all();

        return response()->json([
            'success' => true,
            'roles' => $roles,
        ]);
    }
}
