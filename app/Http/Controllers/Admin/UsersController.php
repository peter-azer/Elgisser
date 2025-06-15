<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules;
use Spatie\Activitylog\Models\Activity;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $users = User::where('role', 'user')->get();
            return response()->json($users);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function getUnauthorizedArtist(){
        try{
            $user = User::where('role', 'artist')
                        ->whereDoesntHave('artist')
                        ->get();
            return response()->json($user);
        }
        catch(\Exception $error){
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }
    public function getUnauthorizedGalleries(){
        try{
            $user = User::where('role', 'gallery')
                        ->whereDoesntHave('gallery')
                        ->get();
            return response()->json($user);
        }
        catch(\Exception $error){
            return response()->json(['error'=>$error->getMessage()], 500);
        }
    }
    public function getAdmins()
    {
        try{
            $users = User::whereIn('role', ['admin', 'super-admin', 'editor'])
                            ->get();
            return response()->json($users);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }

    public function getLogs(){
    $logs = Activity::latest()->with('causer')->get();
    return response()->json($logs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            try{
                    $request->validate([
                        'name' => ['required', 'string', 'max:255'],
                        'name_ar' => ['required', 'string', 'max:255'],
                        'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                        'password' => ['required', Rules\Password::defaults()],
                        'phone' => ['required', 'string', 'max:15'],
                        'address' => ['required', 'string', 'max:255'],
                        'address_ar' => ['required', 'string', 'max:255'],
                        'image' => ['sometimes', 'image', 'max:2048'],
                        'gender' => ['required', 'in:male,female,other'],
                        'role' => ['required', 'string', 'exists:roles,name'],
                    ]);
                if($request->hasFile('image')){
                    $imagePath = $request->file('image')->store('users', 'public');
                    $image = URL::to(Storage::url($imagePath));
                    $request->merge(['image' => $image]);
                }
                
                $user = User::create([
                    'name' => $request->name,
                    'name_ar' => $request->name_ar,
                    'email' => $request->email,
                    'password' => Hash::make($request->input('password')),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                    'address_ar' => $request->input('address_ar'),
                    'image' => $request->input('image'),
                    'gender' => $request->input('gender'),
                    'role' => $request->input('role'),
                ]);
                $role = $request->input('role');
                if ($role) {
                    $user->assignRole($role);
                }
                return response()->json([
                    'message' => 'User created successfully',
                    'user' => $user,
                ], 201);
            }catch(\Exception $error){
                    return response()->json(['error' => $error->getMessage()], 500);
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
        try {
            $validatedData = $request->validate([
                'name' => ['sometimes', 'string', 'max:255'],
                'name_ar' => ['sometimes', 'string', 'max:255'],
                'email' => ['sometimes', 'string', 'email', 'max:255'],
                'password' => ['sometimes', Rules\Password::defaults()],
                'phone' => ['sometimes', 'string', 'max:15'],
                'address' => ['sometimes', 'string', 'max:255'],
                'address_ar' => ['sometimes', 'string', 'max:255'],
                'image' => ['sometimes', 'image', 'max:2048'],
                'gender' => ['sometimes', 'in:male,female,other'],
                'role' => ['sometimes', 'string', 'exists:roles,name'],
            ]);
            if ($request->hasFile('image')) {
                // delete old image
                if ($user->image) {
                    Storage::disk('public')->delete($user->image);
                }
                // upload the new image
                $imagePath = $request->file('image')->store('users', 'public');
                $validatedData['image'] = URL::to(Storage::url($imagePath));
            }
            $user->update($validatedData);
            $role = $request->input('role');
            if ($role) {
                $user->syncRoles([$role]);
            }
            return response()->json(['message' => 'User updated successfully'], 200);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
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
