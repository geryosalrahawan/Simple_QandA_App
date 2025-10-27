<?php
namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function test(Request $request){
        
        return UserRole::Admin;
        
    }
    public function updateRole(Request $request, $id)
    {
        // if (auth()->user()->role !== UserRole::Admin) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        if (auth()->id() == $id) {
            return response()->json(['message' => 'You cannot change your own role'], 403);
        }

        $request->validate([
            'role' => 'required|string|in:admin,user'
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->role = UserRole::from($request->role);
        $user->save();

        return response()->json([
            'message' => 'User role updated successfully',
            'user' => $user
        ]);
    }
}