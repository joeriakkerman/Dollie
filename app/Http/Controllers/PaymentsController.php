<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mollie\Laravel\Facades\Mollie;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Dollie;
use App\Payment;
use App\BankAccount;

use danielme85\CConverter\Currency;

class PaymentsController extends Controller
{

    public function link(Request $req){
        if($this->isAllowed($req)){
            return $this->prepare($req);
        }
        return "You are not linked to this dollie, so you are not able to pay this dollie... ";
    }

    private function isAllowed(Request $req){
        $dollie = Dollie::find($req['dollie_id']);
        if(!empty($dollie) && isset($dollie->name)){
            foreach($dollie->payments as $payment){
                if($payment->payer_id == Auth::user()->id) return true;
            }
        }
        return false;
    }

    public function prepare(Request $req){
        $dollie = Dollie::find($req['dollie_id']);

        $payment = Mollie::api()->payments()->create([
            'amount' => [
                'currency' => strtoupper($dollie->currency),
                'value' => $dollie->amount,
            ],
            'description' => $dollie->description,
            'webhookUrl' => route('payment.webhook'),
            'redirectUrl' => route('index'),
            ]);
        
        $payment = Mollie::api()->payments()->get($payment->id);
        
        DB::table('payments')->where('payer_id', '=', Auth::user()->id)->where('dollie_id', '=', $dollie->id)->update(['payment_id' => $payment->id]);

        return redirect($payment->getCheckoutUrl(), 303);
    }

    public function webhook(Request $req){
        $payment = Mollie::api()->payments()->get($req['id']);
        if ($payment->isPaid()){
            Payment::where("payment_id", "=", $req['id'])->update(['payed' => 1]);
            $p = Payment::where("payment_id", "=", $req['id'])->first();
            $amount = $p->dollie->amount;
            if($p->dollie->currency != "EUR"){
                $currency = new Currency(null, null, false, null, true);
                $amount = $currency->convert($p->dollie->currency, 'EUR', $amount);
                Log::debug("Calculated amount: " . $amount);
            }
            $p->dollie->bankaccount->update(['balance' => $p->dollie->bankaccount->balance + $amount]);
        }
        return "Payment received.";
    }
}
