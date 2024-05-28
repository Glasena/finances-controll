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
                'code' => 'required|string',
                'img' => 'nullable|string'
        
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
                'name' => 'required|string',
                'img' => 'nullable|string'
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

    public function showSingle($id) {
        try {
            $bank = Bank::findOrFail($id);
            return response()->json($bank, 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Bank not found'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    public function showAll() {
        try {
            $banks = Bank::all();
            return response()->json($banks, 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
        

}