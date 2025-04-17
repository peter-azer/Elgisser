<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL; 
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'name_ar' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
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
        event(new Registered($user));

        Auth::login($user);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    /**
     * Update User Data
     */
    public function update(Request $request){
        try {
            $userId = Auth::user()->id;   
            $user = User::findOrFail($userId);
            $validatedData = $request->validate([
                'name' => ['sometimes', 'string', 'max:255'],
                'name_ar' => ['sometimes', 'string', 'max:255'],
                'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['sometimes', 'confirmed', Rules\Password::defaults()],
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

            return response()->json(['message' => 'User updated successfully'], 200);
        }catch(\Exception $error){
            return response()->json(['error' => $error->getMessage()], 500);
        }
    }
}
