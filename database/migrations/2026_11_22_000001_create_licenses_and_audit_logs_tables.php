<?php

declare(strict_types=1);

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
        Schema::create('licenses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('license_key')->unique();
            $table->string('customer_name');
            $table->string('pharmacy_name');
            $table->string('branch_code')->default('BR01');
            $table->string('plan_type'); // STANDARD_LOCAL, PRO_CLOUD_SYNC, ENTERPRISE
            $table->string('billing_type')->default('lifetime'); // lifetime, subscription
            $table->json('limits_json');
            $table->json('features_json');
            $table->string('status')->default('active'); // active, suspended, revoked, expired
            $table->string('bound_hwid', 64)->nullable();
            $table->string('bound_hostname')->nullable();
            $table->integer('max_reset_count')->default(2);
            $table->integer('reset_used')->default(0);
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->integer('grace_days')->default(14);
            $table->integer('heartbeat_interval_days')->default(7);
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->timestamps();
        });

        Schema::create('license_audit_logs', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('license_id')->constrained('licenses')->cascadeOnDelete();
            $table->string('action'); // activated, refreshed, heartbeat, rejected_hwid_mismatch, rejected_revoked, hwid_reset
            $table->string('hwid', 64)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('app_version', 50)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_audit_logs');
        Schema::dropIfExists('licenses');
    }
};
