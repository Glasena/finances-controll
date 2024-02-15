<?php

namespace App\Http\Controllers;

use App\Models\BankTransaction;
use App\Models\IntegrationType;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImportFileController extends Controller {

    public function import($file, IntegrationType $integration_type, $bank_account){

        try {

            switch ($integration_type->id) {
                case 1:
                    $this->importCNAB240($file, $bank_account);
                    break;
                case 2:
                    $this->importBbCsv($file, $bank_account);
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



}