<?php

namespace App\Observers;

use App\Models\Payment;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        $payment->invoice->updatePaidAmount();
    }

    public function updated(Payment $payment): void
    {
        $payment->invoice->updatePaidAmount();
    }

    public function deleted(Payment $payment): void
    {
        $payment->invoice->updatePaidAmount();
    }

    public function restored(Payment $payment): void
    {
    }
}
