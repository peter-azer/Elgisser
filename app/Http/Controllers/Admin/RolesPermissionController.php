<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesPermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $permissions = Permission::all();
        return response()->json([
            'permissions' => $permissions,
        ]);
    }

    /**
     * assign permission to users.
     */
    public function assignPermission(Request $request, $id)
    {
        try{
            $user = User::findOrFail($id);
            $permissions = $request->input('permissions');// get the permissions from the request
            $user->syncPermissions($permissions);// sync the permissions to the user
            return response()->json([
                'message' => 'Permissions assigned successfully',
                'permissions' => $user->getAllPermissions(),
            ]);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Error assigning permissions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
