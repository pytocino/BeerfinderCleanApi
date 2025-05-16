<?php

namespace Database\Factories;

use App\Models\TransactionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionItemFactory extends Factory
{
    protected $model = TransactionItem::class;

    public function definition(): array
    {
        $unitPrice = $this->faker->randomFloat(2, 1, 100);
        $quantity = $this->faker->randomFloat(2, 1, 10);
        $discount = $this->faker->randomFloat(2, 0, 5);
        $taxRate = $this->faker->randomFloat(2, 0, 21);

        $subtotal = $unitPrice * $quantity;
        $subtotalAfterDiscount = $subtotal - $discount;
        $taxAmount = $subtotalAfterDiscount * ($taxRate / 100);
        $total = $subtotalAfterDiscount + $taxAmount;

        $itemableTypes = [
            'App\\Models\\Beer',
            'App\\Models\\Location',
        ];
        $itemableType = $this->faker->randomElement($itemableTypes);
        $itemableModel = new $itemableType;
        $itemableId = $itemableModel->inRandomOrder()->first()?->id;

        return [
            'transaction_id' => \App\Models\Transaction::inRandomOrder()->first()?->id,
            'itemable_id' => $itemableId,
            'itemable_type' => $itemableType,
            'description' => $this->faker->sentence(3),
            'unit_price' => $unitPrice,
            'quantity' => $quantity,
            'discount' => $discount,
            'tax_rate' => $taxRate,
            'total' => $total,
            'additional_data' => json_encode([]),
        ];
    }
}
