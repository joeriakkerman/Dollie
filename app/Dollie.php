<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Dollie extends Model
{
    protected $table = 'dollies';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'name', 'description', 'currency', 'amount', 'account_number', 'dollie_date'];

    public function user(){
        return $this->belongsTo("App\User");
    }

    public function payments(){
        return $this->hasMany("App\Payment", "dollie_id");
    }

    public function bankaccount(){
        return $this->belongsTo("App\BankAccount", "account_number");
    }

    public function extras(){
        return $this->hasOne("App\DollieExtras");
    }

    public static function isAllowed($dollie_id){
        $dollie = Dollie::find($dollie_id);
        if(!empty($dollie) && isset($dollie->name)){
            foreach($dollie->payments as $payment){
                if($payment->payer_id == Auth::user()->id) return true;
            }
        }
        return false;
    }

    public function hasPaymentOpen(){
        foreach($this->payments as $payment){
            if($payment->payer_id == Auth::user()->id)
                if($payment->payed) return true;
                else return false;
        }
        return false;
    }

    public function searchRelevant($search){
        if(strpos(strtolower($this->name), strtolower($search)) !== false) return true;
        else if(strpos(strtolower($this->description), strtolower($search)) !== false) return true;
        else if(strpos(strtolower($this->amount), strtolower($search)) !== false) return true;
        else if(strpos(strtolower($this->user->name), strtolower($search)) !== false) return true;
        else return false;
    }
}
