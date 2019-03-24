<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Dollie;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function dollies(){
        return $this->hasMany("App\Dollie");
    }

    public function payments(){
        return $this->hasMany("App\Payment", "payer_id");
    }

    public static function getUsers($filter){
        if(strlen($filter) <= 0) return;
        $f = "";
        $arr = str_split($filter);
        for($i = 0; $i < count($arr); $i++){
            $f .= "%" . $arr[$i];
        }
        $f .= "%";
        return User::where("name", "LIKE", $f)->get();
    }
}
