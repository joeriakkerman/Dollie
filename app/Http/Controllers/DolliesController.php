<?php

namespace App\Http\Controllers;

use App\Dollie;
use App\DollieExtras;
use App\Payment;
use App\Group;
use App\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class DolliesController extends Controller
{
    public function index(){
        return view('newdollie', ["bankaccounts" => Auth::user()->bank_accounts]);
    }

    public function showDollie(Request $req){
        $dollie = Dollie::find($req["dollie_id"]);
        if(Dollie::isAllowed($req["dollie_id"]) || $dollie->user_id == Auth::user()->id){
            return view('showdollie', ["dollie" => $dollie]);
        }
        return redirect()->back()->withErrors('You are not allowed to see information about this dollie');
    }

    public function verifyDollie(Request $req){
        $name = htmlspecialchars($req['name'], ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($req['description'], ENT_QUOTES, 'UTF-8');
        $currency = htmlspecialchars($req['currency'], ENT_QUOTES, 'UTF-8');
        $amount = htmlspecialchars($req['amount'], ENT_QUOTES, 'UTF-8');
        $account_number = htmlspecialchars($req['account_number'], ENT_QUOTES, 'UTF-8');
        $recurring = htmlspecialchars($req['recurring'], ENT_QUOTES, 'UTF-8');
        $recurring_amount = htmlspecialchars($req['amount_recurring'], ENT_QUOTES, 'UTF-8');
        $dollie_date = htmlspecialchars($req['dollie_date'], ENT_QUOTES, 'UTF-8');
        $filename = htmlspecialchars($req['filename'], ENT_QUOTES, 'UTF-8');
        $payers = array();

        Log::debug("account number in verify dollie function: " . $account_number);

        if(isset($req["payers"])) $payers = json_decode($req["payers"], true);

        if(isset($req['addpayer'])){
            $payers[] = $req['addpayer'];
        }

        if(isset($req['addgroup'])){
            $group = Group::find($req['addgroup']);
            if(isset($group) && !empty($group)){
                foreach($group->members as $member){
                    $payers[] = $member->user->id;
                }
            }
        } 

        if(isset($req['deletepayer'])){
            $pos = array_search($req['deletepayer'], $payers);
            unset($payers[$pos]);
            return view('verifydollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'account_number' => $account_number,'recurring' => $recurring,'recurring_amount' => $recurring_amount, 'dollie_date' => $dollie_date, 'payers' => $payers]);
        }else if(!empty($name) && !empty($desc) && !empty($currency) && !empty($amount) && !empty($account_number) && !empty($dollie_date)){
            if(is_numeric($amount) && $amount > 0){
                $image = $req->file('image');
                if((!isset($filename) || empty($filename)) && $image){
                    $ext = strtolower($image->getClientOriginalExtension());
                    if($ext == "jpg" || $ext == "png" || $ext == "jpeg"){
                        $fname = Auth::user()->id . "_" . date("Y-m-d_h-i-s") . "." . $ext;
                        Log::debug("Save image in storage, filename : " . $filename);
                        Storage::disk('local')->put($fname, File::get($image));
                        $filename = $fname;
                    }else return redirect()->back()->withErrors('The selected file is not an image');
                }

                return view('verifydollie', ['name' => $name, 'description' => $desc, 'currency' => $currency, 'amount' => $amount, 'account_number' => $account_number,'recurring' => $recurring, 'recurring_amount' => $recurring_amount, 'dollie_date' => $dollie_date, 'payers' => $payers, 'filename' => $filename]);
            }else{
                return redirect()->back()->withErrors('Amount should be a positive number');
            }
        }else{
            return redirect()->back()->withErrors('You did not fill in all the required data!');
        }
    }

    public function dollieImage($filename){
        $file = Storage::disk('local')->get($filename);
        return new Response($file, 200);
    }

    private function saveInDb($name, $desc, $currency, $amount, $account_number, $dollie_date, $payers, $filename){
        $dollie = new Dollie;
        $dollie->fill(['user_id' => Auth::user()->id,
                    'name' => $name,
                    'description' => $desc,
                    'currency' => $currency,
                    'amount' => $amount,
                    'dollie_date' => $dollie_date,
                    'account_number' => $account_number]);
        try{
            if(!$dollie->save()){
                return "Could not save dollie in the database";  
            }else{

                if(isset($filename) && !empty($filename)){
                    Log::debug("filename " . $filename);
                    $extras = new DollieExtras;
                    $extras->fill(['dollie_id' => $dollie->id,
                                'filename' => $filename]);
                    if(!$extras->save()){
                        return "Could not add image to this dollie";
                    }
                }else Log::debug("filename is empty");

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
            return "Could not save dollie in the database... Please try again!";
        }
    }

    public function saveDollie(Request $req){
        $name = htmlspecialchars($req['name'], ENT_QUOTES, 'UTF-8');
        $desc = htmlspecialchars($req['description'], ENT_QUOTES, 'UTF-8');
        $currency = htmlspecialchars($req['currency'], ENT_QUOTES, 'UTF-8');
        $amount = htmlspecialchars($req['amount'], ENT_QUOTES, 'UTF-8');
        $account_number = htmlspecialchars($req['account_number'], ENT_QUOTES, 'UTF-8');
        $dollie_date = htmlspecialchars($req['dollie_date'], ENT_QUOTES, 'UTF-8');
        $recurring = htmlspecialchars($req['recurring'], ENT_QUOTES, 'UTF-8');
        $recurring_amount = htmlspecialchars($req['amount_recurring'], ENT_QUOTES, 'UTF-8');
        $filename = htmlspecialchars($req['filename'], ENT_QUOTES, 'UTF-8');

        Log::debug("filename in save dollie " . $filename);
        $payers = array();

        if(isset($req['payers'])) $payers = json_decode($req['payers'], true);

        if(!empty($name) && !empty($desc) && !empty($currency) && !empty($amount) && !empty($account_number)){
            $msg = $this->saveInDb($name, $desc, $currency, $amount, $account_number, $dollie_date, $payers, $filename);
            if($recurring != 'none'){
                $this->scheduledPayment($name, $desc, $currency, $amount, $account_number, $dollie_date, $payers, $recurring, $recurring_amount, $filename);
            }
            if(!isset($msg)){
                return redirect("/");
            }else{
                return redirect()->back()->withErrors($msg);
            }
        }else{
            return redirect()->back()->withErrors('Could not save new dollie in the database... Please try again!');
            }
    }

    public function scheduledPayment($name, $desc, $currency, $amount, $account_number, $dollie_date, $payers, $recurring, $recurring_amount, $filename){
        if($recurring == 'weekly'){
            $startDate = date_create($dollie_date);
            for($x = 0; $x < $recurring_amount; $x++ ){
                $newdate = date_add($startDate, date_interval_create_from_date_string('7 days'));
                $this->saveInDb($name, $desc, $currency, $amount, $account_number, $newdate, $payers, $filename);
            }
        }
        else if($recurring == 'monthly'){
            $beginDate = date_create($dollie_date);
            for($x = 0; $x < $recurring_amount; $x++ ){
                $newdate = date_add($beginDate, date_interval_create_from_date_string('1 month'));
                $this->saveInDb($name, $desc, $currency, $amount, $account_number, $newdate, $payers, $filename);
            }
        }
    }
}
