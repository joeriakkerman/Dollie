<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DollieExtras extends Model
{
    protected $table = 'dollie_extras';
    protected $primaryKey = 'dollie_id';
    protected $fillable = ['dollie_id', 'filename'];

    public $timestamps = false;
    public $incrementing = false;

    public function dollie(){
        return $this->belongsTo("App\Dollie");
    }
}
