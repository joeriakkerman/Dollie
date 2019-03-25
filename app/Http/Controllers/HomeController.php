<?php

namespace App\Http\Controllers;

use App\Dollie;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        $dollies = Auth::user()->dollies;
        return view('home', ["dollies" => $dollies, "filter" => "outgoing", "search" => ""]);
    }

    public function filter(Request $req)
    {
        if($req['filter'] == "incoming"){
            $payments = Auth::user()->payments;
            $dollies = array();
            foreach($payments as $payment){
                if($payment->payer_id == Auth::user()->id){
                    $dollies[] = $payment->dollie;
                }
            }
        }else{
            $dollies = Dollie::where("user_id", "=", Auth::user()->id)->get();
        }
        return view('home', ["dollies" => $dollies, "filter" => $req["filter"], "search" => $req['search']]);
    }

    public function getUsers(Request $req){
        $users = User::getUsers($req["filter"]);
        return $users;
    }

    public function deleteDollie(Request $req){
        $dollie = Dollie::find($req['dollie_id']);
        Log::debug("dollie " . $dollie);
        foreach($dollie->payments as $payment){
            if($payment->payed){
                return redirect()->back()->withErrors("This dollie already has been payed by someone so you can't delete it!");
            }
        }
        $dollie->delete();
        return redirect()->back();
    }
}

/*
class PageController extends Controller
{
    public function page($pageName)
    {
        return view($pageName);
    }
}
*/