<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dollie extends Model
{
    protected $table = 'dollies';
    public $incrementing = false;
    protected $primaryKey = ['user_id', 'name'];
    protected $fillable = ['user_id', 'name', 'description', 'currency', 'amount'];

    public function __construct($userId, $name, $desc, $currency, $amount){
        $this->fill(['user_id' => $userId,
                    'name' => $name,
                    'description' => $desc,
                    'currency' => $currency,
                    'amount' => $amount]);
    }
}
