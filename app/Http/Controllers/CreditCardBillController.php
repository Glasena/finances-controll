<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\IntegrationType;
use Exception;
use Illuminate\Http\Request;
use App\Models\User;
use Smalot\PdfParser\Parser;

class CreditCardBillController extends Controller {

    public function import(Request $request, BankAccount $bank_account) {

        try {

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
    
                $reader->importCreditCard($file, $integrationType, $bank_account);
    
            }            

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        };

    }

    public function store() {
        
    }
    

}