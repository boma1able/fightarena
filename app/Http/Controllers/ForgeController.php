<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ForgeController extends Controller
{
    public function index()
    {
        return view('forge');
    }
}
