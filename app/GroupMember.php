<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{
    protected $table = 'group_members';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = ['group_id', 'user_id'];
    protected $fillable = ['group_id', 'user_id'];

    public function user(){
        return $this->belongsTo("App\User");
    }

    public function group(){
        return $this->belongsTo("App\Group");
    }
}
