<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Group;
use App\GroupMember;

class GroupController extends Controller
{

    public function index(){
        $groups = Auth::user()->groups;
        return view('groups', ["groups" => $groups]);
    }

    public function getGroups(Request $req){
        return Group::getGroups($req['filter']);
    }
    
    public function add(Request $req){
        if(isset($req['groupname'])){
            $msg = $this->addGroup($req['groupname']);
        }
        $groups = Auth::user()->groups;
        if(isset($msg)) return redirect()->back()->withErrors($msg);
        else return view('groups', ["groups" => $groups]);
    }

    public function addMember(Request $req){
        if(isset($req['group_id']) && isset($req['user_id'])){
            $msg = $this->saveMember($req['group_id'], $req['user_id']);
            if(isset($msg)) return redirect()->back()->withErrors($msg);
            else return redirect()->back();
        }
        return redirect()->back()->withErrors("Something went wrong...");
    }

    private function saveMember($group, $user){
        $member = new GroupMember;
        $member->fill(['group_id' => $group,
                        'user_id' => $user]);
        try{
            if(!$member->save()){
                return "Could not add member to group in the database";  
            }
        }catch(\Exception $e){
            Log::debug("Could not add member to group in the database, error message: " . $e->getMessage());
            return "Could not add member to group in the database... Please try again!";
        }
    }

    public function delete(Request $req){
        $group = Group::find($req['group_id']);
        $group->delete();
        return redirect()->back();
    }

    private function addGroup($name){
        $group = new Group;
        $group->fill(['name' => $name,
                    'user_id' => Auth::user()->id]);
        try{
            if(!$group->save()){
                return "Could not create new group in the database";  
            }
        }catch(\Exception $e){
            Log::debug("Could not create new group in the database, error message: " . $e->getMessage());
            return "Could not create new group in the database... Please try again!";
        }
    }
}
