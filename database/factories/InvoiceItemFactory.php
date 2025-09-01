<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class InvoiceItemFactory extends Factory
{
    protected $model = InvoiceItem::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->word(),
            'quantity' => $this->faker->randomNumber(),
            'price' => $this->faker->randomFloat(),
            'total' => $this->faker->randomFloat(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

            'invoice_id' => Invoice::factory(),
        ];
    }
}
