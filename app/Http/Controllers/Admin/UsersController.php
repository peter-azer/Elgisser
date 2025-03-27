<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $users = User::all();
            return response()->json($users);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);
        $role = $request->input('role');
        if ($role) {
            $user->assignRole($role);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        try{
            return response()->json($user);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user->update($validatedData);
        $role = $request->input('role');
        if ($role) {
            $user->syncRoles([$role]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try{
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
