<?php
// routes/web.php
use App\Http\Controllers\PriceController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PriceController::class, 'index']);