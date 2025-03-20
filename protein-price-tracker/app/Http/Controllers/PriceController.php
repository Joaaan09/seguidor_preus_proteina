<?php

// app/Http/Controllers/PriceController.php
namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;

class PriceController extends Controller
{
    public function index()
    {
        $prices = Price::orderBy('created_at', 'desc')->get();
        return view('prices.index', compact('prices'));
    }
}
