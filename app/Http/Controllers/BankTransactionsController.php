<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\IntegrationType;
use App\Models\TransactionCategories;
use Exception;
use Illuminate\Http\Request;

class BankTransactionsController extends Controller {

    public function store(Request $request){
        
        try {

            $validatedData = $request->validate([
                'bank_account_id' => 'required|integer',
                'description' =>  'required|string',
                'value' => 'required|numeric',
                'transaction_category_id' => 'integer',
                'type' => 'string',
            ]);

            $bank_account = BankAccount::find($validatedData['bank_account_id']);

            if(!$bank_account instanceof BankAccount){
                throw new Exception('Bank Account Not Found');
            }

            if (isset($validatedData['transaction_category_id'])) {
                $transaction_category = TransactionCategories::find($validatedData['transaction_category_id']);
            }

            if(isset($transaction_category) && !$transaction_category instanceof TransactionCategories){
                throw new Exception('Transaction Category Not Found');
            }

            if (isset($validatedData['type']) && ($validatedData['type'] != '+' && $validatedData['type'] != '-')) {
                throw new Exception('Invalid Type');
            }

            $bank_transaction = new BankTransaction($validatedData);

            //dd($bank_account);

            if (isset($transaction_category)) {
                $bank_transaction->transaction_category()->associate($transaction_category);
            }
            
            $bank_transaction->bank_account()->associate($bank_account);

            $bank_transaction->save();

        } catch(Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        };

        return response()->json($bank_transaction, 201);

    }


}