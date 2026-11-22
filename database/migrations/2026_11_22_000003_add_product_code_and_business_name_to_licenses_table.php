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
        Schema::table('licenses', function (Blueprint $table) {
            $table->string('product_code', 50)->default('APOTEK_POS')->after('license_key')->index();
            $table->string('business_name')->nullable()->after('pharmacy_name');
        });

        // Sync existing rows
        \Illuminate\Support\Facades\DB::table('licenses')->update([
            'business_name' => \Illuminate\Support\Facades\DB::raw('pharmacy_name'),
            'product_code' => 'APOTEK_POS',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropIndex(['product_code']);
            $table->dropColumn(['product_code', 'business_name']);
        });
    }
};
