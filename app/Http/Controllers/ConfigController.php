<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfigController extends Controller
{
    public function index(Request $request): View
    {
        return view('configuracion.index', [
            'user' => $request->user(),
        ]);
    }
}
