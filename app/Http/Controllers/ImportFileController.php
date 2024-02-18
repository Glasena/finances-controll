<?php

namespace App\Http\Controllers;

use App\Models\BankTransaction;
use App\Models\CreditCardBill;
use App\Models\CreditCardBillsItem;
use App\Models\IntegrationType;
use Smalot\PdfParser\Parser;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportFileController extends Controller {

    public function importTransaction($file, IntegrationType $integration_type, $bank_account){

        try {

            switch ($integration_type->id) {
                case 1:
                    $this->importCNAB240($file, $bank_account);
                    break;
                case 2:
                    $this->importBbCsv($file, $bank_account);
                    break;
                case 3:
                    $this->importNubankCsv($file, $bank_account);
                    break;
                default:
                throw new Exception('Invalid Integration Type');
            }


        } catch(Exception) {
            return response()->json(['message' => 'Validation Error, please check the data sent'], 400);
        };

        return response()->json(201);

    }

    public function importCNAB240($file, $bank_account) {

        $filePath = $file->getRealPath();

        $handle = fopen($filePath, 'r');

        while (($line = fgets($handle)) !== false) {

            if (isset($line[13]) && $line[13] === 'E') {

                $value = floatval(substr($line, 150, 18))/100;

                $date = date_create_from_format('dmY', substr($line, 142, 8));

                $descr = substr($line, 201, 39);

                $type = substr($line, 168, 1);

                $type = $type === 'C' ? '+' : '-';

                $transaction = new BankTransaction();

                $transaction->bank_account()->associate($bank_account);
                $transaction->description = $descr;
                $transaction->date = $date;
                $transaction->value = $value;
                $transaction->type = $type;
                $transaction->save();

            }

        }

        fclose($handle);

    }

    public function importBbCsv($file, $bank_account) {

        $filePath = $file->getRealPath();

        $handle = fopen($filePath, 'r');

        while (($line = fgets($handle)) !== false) {

            $line = str_replace('"', '', $line);

            $content = explode(',', $line);

            $date = $content[0] != '00/00/0000' ? date_create_from_format('d/m/Y', $content[0]) : null;

            $descr = $content[2];
            $bankTypeDescr = $content[1];

            $type = $content[6];
            $type = utf8_encode($type);
            $type = str_replace("\n", "", $type);
            
            $invalidDescr = ($bankTypeDescr == 'Saldo do dia' ||
                             $bankTypeDescr == 'Saldo Anterior' ||
                             $bankTypeDescr == 'S A L D O') ? true : false;

            if (isset($type) && !$invalidDescr) {

                $value = str_replace('.', '', $content[4]);
                $value = str_replace('-', '', $content[4]);

                $value = $value < 0 ? $value * -1 : $value;

                $type = strpos($type, 'Saída') !== false ? '-' : '+';

                $transaction = new BankTransaction();

                $transaction->bank_account()->associate($bank_account);
                $transaction->description = $descr;
                $transaction->date = $date;
                $transaction->value = $value;
                $transaction->type = $type;
                $transaction->save();

            }

        }

        fclose($handle);

    }

    public function importNubankCsv($file, $bank_account) {

        $filePath = $file->getRealPath();

        $handle = fopen($filePath, 'r');

        while (($line = fgets($handle)) !== false) {

            $line = str_replace('"', '', $line);

            $content = explode(',', $line);

            $date = $content[0] != '00/00/0000' ? date_create_from_format('d/m/Y', $content[0]) : null;

            $descr = utf8_encode($content[4]);
            
            $value = $content[2];

            if ($value) {

                $type = strpos($value, '-') !== false ? '-' : '+';
                
                $value = str_replace('.', '', $content[2]);
                $value = str_replace('-', '', $content[2]);

                $value = $value < 0 ? $value * -1 : $value;

                $transaction = new BankTransaction();

                $transaction->bank_account()->associate($bank_account);
                $transaction->description = $descr;
                $transaction->date = $date;
                $transaction->value = $value;
                $transaction->type = $type;
                $transaction->save();

            }

        }

        fclose($handle);

    }

    public function importCreditCard($file, IntegrationType $integration_type, $bank_account){

        try {

            switch ($integration_type->id) {
                case 3:
                    $this->importNubankCreditCard($file, $bank_account);
                    break;
                default:
                throw new Exception('Invalid Integration Type');
            }


        } catch(Exception) {
            return response()->json(['message' => 'Validation Error, please check the data sent'], 400);
        };

        return response()->json(201);

    }

    public function importNubankCreditCard($file, $bank_account) {

        try {

            $parser = new Parser();
            $pdf = $parser->parseFile($file->getPathname());
    
            $text = $pdf->getText();
            
            $position = strpos($text, "TRANSAÇÕES");
    
            $transactionsText = substr($text, $position);
    
            // Define the pattern to search for (NN CCC)
            $pattern = "/\d{2}\s[A-Z]{3}/";
    
            $temptxt = $this->nextPositionString($pattern, $transactionsText); 
            $temptxt = $this->nextPositionString($pattern, $temptxt); 
            $temptxt = $this->nextPositionString($pattern, $temptxt, false);
    
            $resultados = preg_split('/\d{2}\s[A-Z]{3}\s/', $temptxt, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
            
            $counter = 0;
    
            $creditCardBill = new CreditCardBill();
            $creditCardBill->bank_account()->associate($bank_account);
            $creditCardBill->save();
            
            while($counter != count($resultados)) {
    
                $line = explode("\n", $resultados[$counter]);
                
                // Standard Transacion
                if(isset($line) && (count($line) == 2 || count($line) == 3)) {
    
                    $line = explode("\t", $line[0]);
    
                    if (count($line) != 2) {
                        $counter++;
                        continue;
                    }

                    $value = str_replace('.', '', $line[1]);
                    $value = (float)str_replace(',', '.', $line[1]);
                
                    $creditCardBillItem = new CreditCardBillsItem();
                    $creditCardBillItem->credit_card_bills()->associate($creditCardBill);
                    $creditCardBillItem->description = $line[0];
                    $creditCardBillItem->value = $value;
                    $creditCardBillItem->save();

                } 
    
                // International Transacion
                if(isset($line) && count($line) == 5) {
                    
                    $value = str_replace('.', '', $line[3]);
                    $value = (float)str_replace(',', '.', $line[3]);
                
                    $creditCardBillItem = new CreditCardBillsItem();
                    $creditCardBillItem->credit_card_bills()->associate($creditCardBill);
                    $creditCardBillItem->description = $line[0] . ' ' . $line[1] . ' ' . $line[2];
                    $creditCardBillItem->value = $value;
                    $creditCardBillItem->save();

                }
                    
                $counter++;
            }    

        } catch(Exception) {
            return response()->json(['message' => 'Validation Error, please check the data sent'], 400);
        };

        return response()->json(201);

    }

    function nextPositionString($pattern, $text, $next = true) {

        preg_match($pattern, $text, $matches);

        $position = strpos($text, $matches[0]);

        if ($next) {
            $position += 6;
        }

        return substr($text, $position);

    }

}