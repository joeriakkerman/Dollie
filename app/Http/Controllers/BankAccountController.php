<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BankAccount;

class BankAccountController extends Controller
{
    public function index(){
        $bankAccounts = bankaccount::all();
        return view('bankAccountsOverview')->with('bankAccounts', $bankAccounts);
    }

    
}
