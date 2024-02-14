<?php

namespace App\Services;

use App\Models\Bank;

class BanksService extends AbstractService {

    public function __construct(Bank $bank)
    {
        parent::__construct($bank);
    }    

}