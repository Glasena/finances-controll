<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UsersController extends Controller {

    public function store(Request $request){

        try {

            $validatedData = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string',
                'password' => 'required|string'
        
            ]);

            $user = User::create($validatedData);

        } catch(Exception) {
            return response()->json(['message' => 'Validation Error, please check the data sent'], 400);
        };

        return response()->json($user, 201);

    }

    public function delete(User $user) {

        try {
    
            $user->delete();
    
        } catch(Exception) {
            return response()->json(['message' => 'Validation Error, please check the data sent'], 400);
        };
        
        return response()->json(['message' => 'Deleted register'], 204);

    }

}