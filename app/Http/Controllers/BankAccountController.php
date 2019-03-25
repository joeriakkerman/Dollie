<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BankAccount;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends Controller
{

    public function index()
    {
        $bankAccounts = BankAccount ::whereUserId(Auth::id())->get();
        return view('bankAccountsOverview')->with('bankAccounts', $bankAccounts);
    }

    private function encrypt($message){
        $key = openssl_random_pseudo_bytes(18, $cstrong);
        $cipher = "aes-128-gcm";
        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        return openssl_encrypt($message, $cipher, $key, $options=0, $iv, $tag);
    }

    private function decrypt($ciphertext){
        $cipher = "aes-128-gcm";
        $ivlen  = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        return openssl_decrypt($ciphertext, $cipher, $key, $options=0, $iv, $tag);
    }

    private function saveInDb($account){
        $bankAccount = new BankAccount;
        
        $bankAccount->fill([
                    'account_number' => $account,
                    'user_id' => Auth::user()->id,                   
                    'balance' => 100
                    ]);
        try{
            if(!$bankAccount->save()){
                return "Could not save dollie in the database";
            }
        }catch(\Exception $e){
            return "Could not save dollie in the database, error message: " . $e->getMessage();
        }
    }

    public function create(Request $req){
       $account = $req['firstname'];
       if (!preg_match('/^[a-zA-Z]{2}[0-9]{2}[a-zA-Z0-9]{4}[0-9]{7}([a-zA-Z0-9]?){0,16}$/',
                $account)){
                    return redirect()->back()->withErrors("Ongeldige iban");
                } else {
                    $this->saveInDb($account);
                    return redirect()->back();
                }
    }

    public function delete(Request $req){
        BankAccount::where('account_number', $req['bank_account'])->delete();
        return redirect()->back();
    }
}
