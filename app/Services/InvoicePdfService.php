<?php

namespace App\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoicePdfService
{
    public function download(Invoice $invoice)
    {
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }

    public function stream(Invoice $invoice)
    {
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream("invoice-{$invoice->invoice_number}.pdf");
    }
}
