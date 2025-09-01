<?php

use App\Http\Controllers\InvoicePdfController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Invoice PDF routes
Route::get('/invoices/{invoice}/pdf', [InvoicePdfController::class, 'generate'])
    ->name('invoices.pdf');
Route::get('/invoices/{invoice}/pdf/preview', [InvoicePdfController::class, 'preview'])
    ->name('invoices.pdf.preview');
