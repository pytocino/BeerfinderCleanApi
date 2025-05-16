<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
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
        $statuses = [
            'pending',
            'completed',
            'failed',
            'refunded',
            'partially_refunded',
            'disputed',
            'cancelled',
        ];
        $types = [
            'purchase',
            'subscription',
            'refund',
            'credit',
            'debit',
            'transfer',
        ];
        $paymentMethods = [
            'stripe',
            'paypal',
            'bank_transfer',
            'credit_card',
            'apple_pay',
            'google_pay',
        ];

        // Polimórfico: Beer o Location
        $transactionableTypes = [
            'App\\Models\\Beer',
            'App\\Models\\Location',
        ];
        $transactionableType = $this->faker->randomElement($transactionableTypes);
        $transactionableModel = new $transactionableType;
        $transactionableId = $transactionableModel->inRandomOrder()->first()?->id;

        return [
            'transaction_uuid' => (string) Str::uuid(),
            'user_id' => User::inRandomOrder()->first()?->id,
            'transactionable_id' => $transactionableId,
            'transactionable_type' => $transactionableType,
            'amount' => $amount,
            'currency' => $currency,
            'tax_amount' => $taxAmount,
            'status' => $this->faker->randomElement($statuses),
            'type' => $this->faker->randomElement($types),
            'payment_method' => $this->faker->randomElement($paymentMethods),
            'payment_id' => $this->faker->optional()->uuid(),
            'payment_details' => null,
            'notes' => $this->faker->optional()->sentence(),
            'processed_by' => User::inRandomOrder()->first()?->id,
            'processed_at' => $this->faker->optional()->dateTimeThisYear(),
            'refunded_at' => $this->faker->optional(0.2)->dateTimeThisYear(),
        ];
    }
}
