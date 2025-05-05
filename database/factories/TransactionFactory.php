<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        $amount = $this->faker->randomFloat(2, 10, 500);
        $taxAmount = $this->faker->randomFloat(2, 0, 50);
        $currency = $this->faker->randomElement(['USD', 'EUR', 'GBP', 'JPY']);
        $status = $this->faker->randomElement([
            Transaction::STATUS_PENDING,
            Transaction::STATUS_COMPLETED,
            Transaction::STATUS_FAILED,
            Transaction::STATUS_REFUNDED,
            Transaction::STATUS_PARTIALLY_REFUNDED,
            Transaction::STATUS_DISPUTED,
            Transaction::STATUS_CANCELLED,
        ]);
        $type = $this->faker->randomElement([
            Transaction::TYPE_PURCHASE,
            Transaction::TYPE_SUBSCRIPTION,
            Transaction::TYPE_REFUND,
            Transaction::TYPE_CREDIT,
            Transaction::TYPE_DEBIT,
            Transaction::TYPE_TRANSFER,
        ]);
        $paymentMethod = $this->faker->randomElement([
            Transaction::PAYMENT_STRIPE,
            Transaction::PAYMENT_PAYPAL,
            Transaction::PAYMENT_BANK_TRANSFER,
            Transaction::PAYMENT_CREDIT_CARD,
            Transaction::PAYMENT_APPLE_PAY,
            Transaction::PAYMENT_GOOGLE_PAY,
        ]);

        return [
            'transaction_uuid' => (string) Str::uuid(),
            'user_id' => null, // Asignar en el seeder o test
            'transactionable_id' => null,
            'transactionable_type' => null,
            'amount' => $amount,
            'currency' => $currency,
            'tax_amount' => $taxAmount,
            'status' => $status,
            'type' => $type,
            'payment_method' => $paymentMethod,
            'payment_id' => $this->faker->optional()->uuid(),
            'payment_details' => [],
            'notes' => $this->faker->optional()->sentence(),
            'processed_by' => null,
            'processed_at' => $this->faker->optional()->dateTimeThisYear(),
            'refunded_at' => $status === Transaction::STATUS_REFUNDED ? $this->faker->dateTimeThisYear() : null,
        ];
    }
}
