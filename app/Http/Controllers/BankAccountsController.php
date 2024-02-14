<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\IntegrationType;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;

class BankAccountsController extends Controller {

    public function store(Request $request){
        
        try {

            $validatedData = $request->validate([
                'account_number' => 'required|string',
                'bank_id' =>  'required|integer',
                'integration_type_id' => 'required|integer',
                'user_id' => 'required|integer'
            ]);

            $user = User::find($validatedData['user_id']);

            if(!$user instanceof User){
                throw new Exception('User Not Found');
            }

            $bank = Bank::find($validatedData['bank_id']);

            if(!$bank instanceof Bank){
                throw new Exception('Bank Not Found');
            }

            $integration_type = IntegrationType::find($validatedData['integration_type_id']);

            if(!$integration_type instanceof IntegrationType){
                throw new Exception('Integration Type Not Found');
            }

            $bank_account = new BankAccount($validatedData);

            //dd($bank_account);

            $bank_account->user()->associate($user);
            $bank_account->bank()->associate($bank);
            $bank_account->integration_type()->associate($integration_type);

            $bank_account->save();

        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        };

        return response()->json($bank_account, 201);

    }

}