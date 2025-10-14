<?php

namespace App\Observers;

use App\Models\OrderCustomer;
use Illuminate\Support\Facades\DB;

class OrderCustomerObserver
{
    public function saved(OrderCustomer $orderCustomer): void
    {
        DB::afterCommit(function () use ($orderCustomer) {
            if ($orderCustomer->wasRecentlyCreated) {
                $orderCustomer->createInvoice();
            } else {
                $orderCustomer->updateInvoice();
            }
        });
    }
}
