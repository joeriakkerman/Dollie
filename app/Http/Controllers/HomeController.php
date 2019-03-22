<?php

namespace App\Http\Controllers;

use App\Dollie;
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
        //$dollies = Auth::user()->getIncomingDollies();
        return view('home', ["dollies" => $dollies, "filter" => $req["filter"]]);
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