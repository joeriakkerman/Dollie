<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DolliesController extends Controller
{
    public function index(){
        return view('newdollie');
    }
}
