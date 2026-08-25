<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class CheckoutPageController extends Controller
{
    public function __invoke(): View
    {
        return view('checkout');
    }
}