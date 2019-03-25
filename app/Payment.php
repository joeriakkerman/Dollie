<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';
    public $incrementing = false;
    protected $primaryKey = ['dollie_id', 'payer_id'];
    protected $fillable = ['dollie_id', 'payer_id', 'payment_id', 'payed'];

    public function dollie(){
        return $this->belongsTo("App\Dollie");
    }

    public function payer(){
        return $this->belongsTo("App\User");
    }
}
