<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditCardBillsItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_credit-card-bill',
        'description',
        'value',
        'date'
    ];

    public function credit_card_bills()
    {
        return $this->belongsTo(CreditCardBill::class);
    }

}
