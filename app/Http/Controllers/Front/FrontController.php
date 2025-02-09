<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontController extends Controller
{

    public function home() {
        return view('welcome');
    }

    public function payment() {
        return view('front.payment');
    }
}
