<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dollie extends Model
{
    protected $table = 'dollies';
    protected $primaryKey = 'id';
    protected $fillable = ['id', 'user_id', 'name', 'description', 'currency', 'amount'];

    public function user(){
        return $this->belongsTo("App\User");
    }
}
