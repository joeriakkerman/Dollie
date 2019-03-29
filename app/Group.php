<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Group extends Model
{
    protected $table = 'groups';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['name', 'user_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function members(){
        return $this->hasMany("App\GroupMember");
    }

    public function getFormattedMembers(){
        $members = $this->members;
        $format = "";
        for($i = 0; $i < $members->count(); $i++){
            $format .= $members[$i]->user->name;
            if($i < ($members->count() - 1)) $format .= ", ";
        }
        return $format;
    }

    public static function getGroups($filter){
        if(strlen($filter) <= 0) return;
        $f = "";
        $arr = str_split($filter);
        for($i = 0; $i < count($arr); $i++){
            $f .= "%" . $arr[$i];
        }
        $f .= "%";
        return Group::where("name", "LIKE", $f)->where("user_id", "=", Auth::user()->id)->get();
    }
}
