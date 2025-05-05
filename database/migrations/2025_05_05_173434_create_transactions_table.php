<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('transaction_uuid')->unique(); // ID único para referencias externas
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->morphs('transactionable'); // Modelo relacionado (producto, suscripción, etc)

            // Detalles financieros
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->decimal('tax_amount', 10, 2)->default(0);

            // Estado y tipo
            $table->enum('status', [
                'pending',
                'completed',
                'failed',
                'refunded',
                'partially_refunded',
                'disputed',
                'cancelled'
            ])->default('pending');
            $table->enum('type', [
                'purchase',
                'subscription',
                'refund',
                'credit',
                'debit',
                'transfer'
            ]);

            // Detalles de pago
            $table->string('payment_method')->nullable(); // stripe, paypal, etc
            $table->string('payment_id')->nullable(); // ID de referencia del sistema de pago
            $table->json('payment_details')->nullable(); // Datos adicionales (tokenizados)

            // Detalles administrativos
            $table->text('notes')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();

            // Fechas específicas
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('refunded_at')->nullable();

            // Campos estándar
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('status');
            $table->index('type');
            $table->index('created_at');
        });

        // Tabla para elementos de la transacción (útil para compras con múltiples ítems)
        Schema::create('transaction_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->morphs('itemable'); // Producto, servicio, etc.
            $table->string('description');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('quantity', 8, 2)->default(1);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->json('additional_data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_items');
        Schema::dropIfExists('transactions');
    }
};
