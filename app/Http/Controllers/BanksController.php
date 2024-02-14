<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Services\BanksService;
use Exception;
use Illuminate\Http\Request;

class BanksController extends Controller {

    protected $banksService;

    public function __construct(BanksService $banksService)
    {
        $this->banksService = $banksService;
    }

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

    public function show($id) {
        try {

            $bank = Bank::find($id);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json($bank, 200);

    }
    

}