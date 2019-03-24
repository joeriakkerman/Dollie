<?php

namespace App\Http\Controllers;

use App\Dollie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        if(!isset($req["payers"])) $req["payers"] = array();

        if(isset($req['addpayer'])) $req["payers"][] = $req['addpayer'];

        if(isset($req['deletepayer'])){
            $pos = array_search($req['deletepayer'], $req["payers"]);
            unset($req['payers'][$pos]);
            return view('newdollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'payers' => $req['payers']]);
        }else if(!empty($name) && !empty($desc) && !empty($currency) && !empty($amount)){
            if(is_numeric($amount) && $amount > 0){
                return view('newdollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'payers' => $req['payers']]);
            }else
                return view('newdollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'payers' => $req['payers'], 'message' => "Amount should be a positive number"]);
        }else{
            return view('newdollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'payers' => $req['payers'], 'message' => "You did not fill in all the required data!"]);
        }
    }

    private function saveInDb($name, $desc, $currency, $amount){
        $dollie = new Dollie;
        $dollie->fill(['user_id' => Auth::user()->id,
                    'name' => $name,
                    'description' => $desc,
                    'currency' => $currency,
                    'amount' => $amount]);
        try{
            if(!$dollie->save()){
                return "Could not save dollie in the database";  
            }
        }catch(\Exception $e){
            return "Could not save dollie in the database, error message: " . $e->getMessage();
        }
    }

    public function saveDollie(Request $req){
        $name = htmlspecialchars($req['name'], ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($req['description'], ENT_QUOTES, 'UTF-8');
        $currency = htmlspecialchars($req['currency'], ENT_QUOTES, 'UTF-8');
        $amount = htmlspecialchars($req['amount'], ENT_QUOTES, 'UTF-8');
        if(!empty($name) && !empty($desc) && !empty($currency) && !empty($amount)){
            $msg = $this->saveInDb($name, $desc, $currency, $amount);
            if(!isset($msg)){
                return redirect("/");
            }else{
                return view('newdollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'error' => $msg]);
            }
        }else{
            return view('newdollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'error' => 'Could not save new dollie in the database... Please try again!']);
        }
    }
}
