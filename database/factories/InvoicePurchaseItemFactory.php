<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoicePurchaseItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InvoicePurchaseItemFactory extends Factory
{
    protected $model = InvoicePurchaseItem::class;

    public function definition(): array
    {
        return [
            'description' => $this->faker->text(),
            'cost' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'invoice_id' => Invoice::factory(),
        ];
    }
}
