<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use misterspelik\LaravelPdf\Facades\Pdf;

class PrintInvoiceController extends Controller
{
    public function index(Invoice $invoice)
    {
        // Load relationships
        $invoice->load(['customer', 'items', 'payments']);

        // Generate PDF
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        // Return PDF as download
        return $pdf->stream("invoice-{$invoice->id}.pdf");
    }
}
