<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dollie extends Model
{
    protected $table = 'dollies';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'name', 'description', 'currency', 'amount', 'account_number'];

    public function user(){
        return $this->belongsTo("App\User");
    }

    public function payments(){
        return $this->hasMany("App\Payment", "dollie_id");
    }

    public function bankaccount(){
        return $this->belongsTo("App\BankAccount", "account_number");
    }

    public function searchRelevant($search){
        if(strpos(strtolower($this->name), strtolower($search)) !== false) return true;
        else if(strpos(strtolower($this->description), strtolower($search)) !== false) return true;
        else if(strpos(strtolower($this->amount), strtolower($search)) !== false) return true;
        else if(strpos(strtolower($this->user->name), strtolower($search)) !== false) return true;
        else return false;
    }
}
