<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use misterspelik\LaravelPdf\Facades\Pdf;

class InvoicePdfController extends Controller
{
    public function generate(Invoice $invoice)
    {
        // Load relationships
        $invoice->load(['customer', 'items', 'payments']);

        // Generate PDF
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

//        $pdf->setWatermarkImage(public_path('images/logo.png'), 0.2, 'F', 'F'); // path, alpha, 'F' for fixed size, 'F' for fixed position
        $pdf->showWatermarkImage = true;

        // Return PDF as download
        return $pdf->stream("invoice-{$invoice->id}.pdf");
    }
}
