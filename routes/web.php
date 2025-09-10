<?php

use App\Http\Controllers\PrintInvoiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Invoice PDF routes
Route::get('/invoices/{invoice}/print', [PrintInvoiceController::class, 'index'])
    ->name('invoices.print');
