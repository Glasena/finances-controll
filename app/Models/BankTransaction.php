<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankTransaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_bank-account',
        'description',
        'value',
        'id_transaction-category',
        'type'
    ];

    public function transaction_category()
    {
        return $this->belongsTo(TransactionCategories::class);
    }

    public function bank_account()
    {
        return $this->belongsTo(BankAccount::class);
    }

}
