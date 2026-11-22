<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Branches Table
        Schema::create('branches', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('branch_code')->unique();
            $table->string('branch_name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Cloud Transactions (Aggregated across branches for owner analytics)
        Schema::create('cloud_transactions', function (Blueprint $table) {
            $table->ulid('id')->primary(); // ULID from local terminal ensures idempotency
            $table->string('branch_code');
            $table->string('invoice_no');
            $table->string('cashier_name')->nullable();
            $table->string('type')->default('otc');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('tuslah_embalase_total', 15, 2)->default(0.00);
            $table->decimal('discount_total', 15, 2)->default(0.00);
            $table->decimal('grand_total', 15, 2);
            $table->decimal('hpp_total', 15, 2)->default(0.00);
            $table->decimal('gross_profit', 15, 2)->default(0.00);
            $table->string('payment_method')->default('cash');
            $table->string('status')->default('completed');
            $table->timestamp('transaction_date');
            $table->timestamps();

            $table->index(['branch_code', 'transaction_date']);
            $table->index(['invoice_no']);
        });

        // 3. Cloud Transaction Items
        Schema::create('cloud_transaction_items', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('cloud_transaction_id')->constrained('cloud_transactions')->cascadeOnDelete();
            $table->string('product_name');
            $table->string('batch_number');
            $table->decimal('qty', 10, 2);
            $table->string('unit_name')->default('Tablet');
            $table->decimal('unit_price', 15, 2);
            $table->decimal('hpp_unit', 15, 2)->default(0.00);
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();
        });

        // 4. Cloud Shifts
        Schema::create('cloud_shifts', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('branch_code');
            $table->string('terminal_id');
            $table->string('cashier_name')->nullable();
            $table->timestamp('opened_at');
            $table->timestamp('closed_at')->nullable();
            $table->decimal('opening_cash', 15, 2);
            $table->decimal('actual_cash', 15, 2)->nullable();
            $table->decimal('cash_difference', 15, 2)->nullable();
            $table->string('status')->default('open');
            $table->timestamps();

            $table->index(['branch_code', 'opened_at']);
        });

        // 5. Cloud Inventory Batches (Stock & Expiry Alert)
        Schema::create('cloud_inventory_batches', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('branch_code');
            $table->string('product_name');
            $table->string('batch_number');
            $table->date('expired_date');
            $table->integer('stock_qty');
            $table->string('unit_name')->default('Tablet');
            $table->decimal('buy_price', 15, 2)->default(0.00);
            $table->timestamp('synced_at')->useCurrent();
            $table->timestamps();

            $table->index(['branch_code', 'expired_date']);
            $table->index(['branch_code', 'stock_qty']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cloud_inventory_batches');
        Schema::dropIfExists('cloud_shifts');
        Schema::dropIfExists('cloud_transaction_items');
        Schema::dropIfExists('cloud_transactions');
        Schema::dropIfExists('branches');
    }
};
