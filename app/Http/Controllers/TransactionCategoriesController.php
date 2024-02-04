<?php

namespace App\Http\Controllers;

use App\Models\TransactionCategories;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class TransactionCategoriesController extends Controller {

    public function store(Request $request, User $user){
        
        try {

            $validatedData = $request->validate([
                'description' => 'required|string'        
            ]);
            
            if(!$user instanceof User){
                throw new Exception();
            }

            $transaction_category = new TransactionCategories($validatedData);
            $transaction_category->user()->associate($user);
            $transaction_category->save();

        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        };

        return response()->json($transaction_category, 201);

    }
    
}