<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use App\Models\BankTransaction;
use App\Models\IntegrationType;
use App\Models\TransactionCategories;
use App\Models\User;
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

    public function import(Request $request, BankAccount $bank_account) {

        if(!$bank_account instanceof BankAccount) {
            throw new Exception('Invalid Bank Account');
        }

        $integrationTypeId = $bank_account->getAttribute('integration_type_id');

        $integrationType = IntegrationType::find($integrationTypeId);

        if(!$integrationType instanceof IntegrationType) {
            throw new Exception('Integration Type');
        }        

        foreach ($request->allFiles() as $file) {

            $reader = new ImportFileController();

            $reader->importTransaction($file, $integrationType, $bank_account);

            $file->store('pasta');
        }        

    }

    public function updatetransactioncategory(Request $request, BankTransaction $bankTransaction) {

        try {

            $validatedData = $request->validate([
                'transaction_category_id' => 'required|integer'
            ]);
        
            $transactionCategory = TransactionCategories::find($validatedData['transaction_category_id']);
    
            if(!$transactionCategory instanceof TransactionCategories) {
                throw new Exception('Transaction Category Not Found !');
            }  
            
            $bankTransaction->transaction_category()->associate($transactionCategory);
            $bankTransaction->save();


        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        return response()->json($bankTransaction, 201);
    }

}