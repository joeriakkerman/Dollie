<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DolliesController extends Controller
{
    public function index(){
        return view('newdollie');
    }

    public function verifyDollie(Request $req){
        $name = htmlspecialchars($req['name'], ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($req['description'], ENT_QUOTES, 'UTF-8');
        $currency = htmlspecialchars($req['currency'], ENT_QUOTES, 'UTF-8');
        $amount = htmlspecialchars($req['amount'], ENT_QUOTES, 'UTF-8');
        if(isset($name) && isset($desc) && isset($currency) && isset($amount)){
            if(is_numeric($amount) && $amount > 0)
                return view('newdollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'success' => 1]);
            else
                return view('newdollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'message' => "Amount should be a positive number"]);
        }else{
            return view('newdollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'message' => "You did not fill in all the required data!"]);
        }
    }

    public function saveDollie(Request $req){
        $name = htmlspecialchars($req['name'], ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($req['description'], ENT_QUOTES, 'UTF-8');
        $currency = htmlspecialchars($req['currency'], ENT_QUOTES, 'UTF-8');
        $amount = htmlspecialchars($req['amount'], ENT_QUOTES, 'UTF-8');
        if(isset($name) && isset($desc) && isset($currency) && isset($amount)){
            saveInDb($name, $desc, $currency, $amount);
        }else{
            return view('newdollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'error' => 'Could not save new dollie in the database... Please try again!']);
        }
    }

    private function saveInDb($name, $desc, $currency, $amount){

    }
}
