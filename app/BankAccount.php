<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'bankaccount';
    protected $fillable = ['account_number', 'user_id', 'balance'];
    protected $primaryKey = 'account_number';
    public $incrementing = false;
}
