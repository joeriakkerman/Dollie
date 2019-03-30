<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Validator;
use Calendar;
use App\Events;
use App\Dollie;


class EventsController extends Controller
{
    public function index(){
        $events = [];
        $data = Auth::user()->dollies;
        if($data->count()) {
            foreach ($data as $key => $value) {
                $events[] = Calendar::event(
                    $value->name,
                    true,
                    new \DateTime($value->dollie_date),
                    new \DateTime($value->dollie_date)
                );
            }
        }
    
        $calendar = Calendar::addEvents($events); 
        return view('events', compact('calendar'));
    }
}
