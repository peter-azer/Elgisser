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
}
