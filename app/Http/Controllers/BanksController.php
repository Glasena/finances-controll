<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Exception;
use Illuminate\Http\Request;

class BanksController extends Controller {

    public function store(Request $request){

        try {

            $validatedData = $request->validate([
                'name' => 'required|string',
                'code' => 'required|string'
        
            ]);

            $bank = Bank::create($validatedData);

        } catch(Exception) {
            return response()->json(['message' => 'Validation Error, please check the data sent'], 400);
        };

        return response()->json($bank, 201);

    }

    public function update(Request $request, Bank $bank) {        

        try {

            $validatedData = $request->validate([
                'name' => 'required|string'
            ]);
    
            $bank->update($validatedData);
    
        } catch(Exception) {
            return response()->json(['message' => 'Validation Error, please check the data sent'], 400);
        };
        
        return response()->json($bank, 200);

    }

    public function delete(Bank $bank) {

        try {
    
            $bank->delete();
    
        } catch(Exception) {
            return response()->json(['message' => 'Validation Error, please check the data sent'], 400);
        };
        
        return response()->json(['message' => 'Deleted register'], 204);

    }

}