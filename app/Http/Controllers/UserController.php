<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class UserController extends Controller
{
    public function policy(){
        return view('policy');
    }
        public function update(Request $request, User $user)
    {
        try {
            $validatedData = $request->validate([
                'name' => ['sometimes', 'string', 'max:255'],
                'name_ar' => ['sometimes', 'string', 'max:255'],
                'email' => ['sometimes', 'string', 'email', 'max:255'],
                'phone' => ['sometimes', 'string', 'max:15'],
                'address' => ['sometimes', 'string', 'max:255'],
                'address_ar' => ['sometimes', 'string', 'max:255'],
                'image' => ['sometimes', 'image', 'max:2048'],
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
