<?php

namespace App\Http\Controllers;

use App\Dollie;
use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DolliesController extends Controller
{
    public function index(){
        return view('newdollie', ["bankaccounts" => Auth::user()->bank_accounts]);
    }

    public function verifyDollie(Request $req){
        $name = htmlspecialchars($req['name'], ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($req['description'], ENT_QUOTES, 'UTF-8');
        $currency = htmlspecialchars($req['currency'], ENT_QUOTES, 'UTF-8');
        $amount = htmlspecialchars($req['amount'], ENT_QUOTES, 'UTF-8');
        $account_number = htmlspecialchars($req['account_number'], ENT_QUOTES, 'UTF-8');
        $payers = array();

        Log::debug("account number in verify dollie function: " . $account_number);

        if(isset($req["payers"])) $payers = json_decode($req["payers"], true);

        if(isset($req['addpayer'])){
            $payers[] = $req['addpayer'];
        } 

        if(isset($req['deletepayer'])){
            $pos = array_search($req['deletepayer'], $payers);
            unset($payers[$pos]);
            return view('verifydollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'account_number' => $account_number, 'payers' => $payers]);
        }else if(!empty($name) && !empty($desc) && !empty($currency) && !empty($amount) && !empty($account_number)){
            if(is_numeric($amount) && $amount > 0){
                return view('verifydollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'account_number' => $account_number, 'payers' => $payers]);
            }else{
                return redirect()->back()->withErrors('Amount should be a positive number');
            }
        }else{
            return redirect()->back()->withErrors('You did not fill in all the required data!');
        }
    }

    private function saveInDb($name, $desc, $currency, $amount, $account_number, $payers){
        $dollie = new Dollie;
        $dollie->fill(['user_id' => Auth::user()->id,
                    'name' => $name,
                    'description' => $desc,
                    'currency' => $currency,
                    'amount' => $amount,
                    'account_number' => $account_number]);
        try{
            if(!$dollie->save()){
                return "Could not save dollie in the database";  
            }else{
                foreach($payers as $payer){
                    $payment = new Payment;
                    $payment->fill(['dollie_id' => $dollie->id, 'payer_id' => $payer, 'payed' => false]);

                    if(!$payment->save()){
                        return "Could not save dollie for the specified users";
                    }
                }
            }
        }catch(\Exception $e){
            Log::debug("Could not save dollie in the database, error message: " . $e->getMessage());
            return "Could not save dollie in the database... Please try again! " . $e->getMessage();
        }
    }

    public function saveDollie(Request $req){
        $name = htmlspecialchars($req['name'], ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($req['description'], ENT_QUOTES, 'UTF-8');
        $currency = htmlspecialchars($req['currency'], ENT_QUOTES, 'UTF-8');
        $amount = htmlspecialchars($req['amount'], ENT_QUOTES, 'UTF-8');
        $account_number = htmlspecialchars($req['account_number'], ENT_QUOTES, 'UTF-8');
        $payers = array();

        if(isset($req['payers'])) $payers = json_decode($req['payers'], true);

        if(!empty($name) && !empty($desc) && !empty($currency) && !empty($amount) && !empty($account_number)){
            $msg = $this->saveInDb($name, $desc, $currency, $amount, $account_number, $payers);
            if(!isset($msg)){
                return redirect("/");
            }else{
                return redirect()->back()->withErrors($msg);
            }
        }else{
            return redirect()->back()->withErrors('Could not save new dollie in the database... Please try again!');
        }
    }
}
