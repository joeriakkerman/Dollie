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

class PaymentsController extends Controller
{

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
            $p->dollie->bankaccount->update(['balance' => $p->dollie->bankaccount->balance + $p->dollie->amount]);
        }
        return "Payment received.";
    }
}
