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
        return view('home', ["dollies" => $dollies, "filter" => "outgoing"]);
    }

    public function filter(Request $req)
    {
        $payments = Auth::user()->payments;
        $dollies = array();
        foreach($payments as $payment){
            if($payment->payer_id == Auth::user()->id)
                $dollies[] = $payment->dollie;
        }
        return view('home', ["dollies" => $dollies, "filter" => $req["filter"]]);
    }

    public function getUsers(Request $req){
        Log::debug("getUsers -> " . $req["filter"]);
        $users = User::getUsers($req["filter"]);
        return $users;
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