<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';
    protected $fillable = [
        'amount',
        'description',
        'from_bank_id',
        'to_bank_id',
        'currency'
    ];
}
