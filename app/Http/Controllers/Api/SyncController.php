<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CloudInventoryBatch;
use App\Models\CloudShift;
use App\Models\CloudTransaction;
use App\Models\CloudTransactionItem;
use App\Models\License;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    /**
     * Idempotent Push Endpoint: receive batches of transactions, shifts, and stock levels from POS branches.
     */
    public function push(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'license_key' => ['required', 'string'],
            'branch_code' => ['required', 'string'],
            'transactions' => ['nullable', 'array'],
            'shifts' => ['nullable', 'array'],
            'batches' => ['nullable', 'array'],
        ]);

        $license = License::where('license_key', $validated['license_key'])->first();
        if (! $license || $license->status !== 'active') {
            return response()->json([
                'status' => 'error',
                'message' => 'Lisensi tidak aktif atau tidak terdaftar di Cloud Hub.',
            ], 403);
        }

        // Check tier entitlement for Cloud Sync Push
        $features = $license->features_json ?? [];
        if (empty($features['cloud_sync_push'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fitur Cloud Sync Push tidak termasuk dalam paket lisensi ini. Upgrade ke Paket PRO diperlukan.',
            ], 403);
        }

        $branchCode = $validated['branch_code'];
        $txCount = 0;
        $shiftCount = 0;
        $batchCount = 0;

        DB::transaction(function () use ($validated, $branchCode, &$txCount, &$shiftCount, &$batchCount) {
            // 1. Process Transactions (Idempotent by ULID)
            foreach ($validated['transactions'] ?? [] as $tx) {
                $subtotal = (string) ($tx['subtotal'] ?? '0.00');
                $grandTotal = (string) ($tx['grand_total'] ?? '0.00');

                // Compute HPP and Gross Profit from items snapshot
                $totalHpp = '0.00';
                foreach ($tx['items'] ?? [] as $item) {
                    $itemHpp = bcmul((string) ($item['hpp_base_snapshot'] ?? '0.00'), (string) ($item['qty_base_deducted'] ?? $item['qty_input'] ?? '1'), 2);
                    $totalHpp = bcadd($totalHpp, $itemHpp, 2);
                }
                $grossProfit = bcsub($grandTotal, $totalHpp, 2);

                $cloudTx = CloudTransaction::updateOrCreate(
                    ['id' => $tx['id']],
                    [
                        'branch_code' => $branchCode,
                        'invoice_no' => $tx['invoice_no'],
                        'cashier_name' => $tx['cashier_name'] ?? 'Kasir',
                        'type' => $tx['type'] ?? 'otc',
                        'subtotal' => $subtotal,
                        'tuslah_embalase_total' => (string) ($tx['tuslah_embalase_total'] ?? '0.00'),
                        'discount_total' => (string) ($tx['discount_total'] ?? '0.00'),
                        'grand_total' => $grandTotal,
                        'hpp_total' => $totalHpp,
                        'gross_profit' => $grossProfit,
                        'payment_method' => $tx['payment_method'] ?? 'cash',
                        'status' => $tx['status'] ?? 'completed',
                        'transaction_date' => $tx['created_at'] ?? now(),
                    ]
                );

                foreach ($tx['items'] ?? [] as $item) {
                    CloudTransactionItem::updateOrCreate(
                        ['id' => $item['id']],
                        [
                            'cloud_transaction_id' => $cloudTx->id,
                            'product_name' => $item['product_name'] ?? 'Obat',
                            'batch_number' => $item['batch_number'] ?? 'BATCH-DEFAULT',
                            'qty' => $item['qty_input'] ?? 1,
                            'unit_name' => $item['unit_name'] ?? 'Tablet',
                            'unit_price' => (string) ($item['unit_price'] ?? '0.00'),
                            'hpp_unit' => (string) ($item['hpp_base_snapshot'] ?? '0.00'),
                            'subtotal' => (string) ($item['subtotal'] ?? '0.00'),
                        ]
                    );
                }
                $txCount++;
            }

            // 2. Process Shifts (Idempotent by ULID)
            foreach ($validated['shifts'] ?? [] as $shift) {
                CloudShift::updateOrCreate(
                    ['id' => $shift['id']],
                    [
                        'branch_code' => $branchCode,
                        'terminal_id' => $shift['terminal_id'] ?? 'POS01',
                        'cashier_name' => $shift['cashier_name'] ?? null,
                        'opened_at' => $shift['opened_at'] ?? now(),
                        'closed_at' => $shift['closed_at'] ?? null,
                        'opening_cash' => (string) ($shift['opening_cash'] ?? '0.00'),
                        'actual_cash' => isset($shift['actual_cash']) ? (string) $shift['actual_cash'] : null,
                        'cash_difference' => isset($shift['cash_difference']) ? (string) $shift['cash_difference'] : null,
                        'status' => $shift['status'] ?? 'open',
                    ]
                );
                $shiftCount++;
            }

            // 3. Process Batches / Stock Levels (Idempotent by ULID)
            foreach ($validated['batches'] ?? [] as $batch) {
                CloudInventoryBatch::updateOrCreate(
                    ['id' => $batch['id']],
                    [
                        'branch_code' => $branchCode,
                        'product_name' => $batch['product_name'] ?? 'Obat',
                        'batch_number' => $batch['batch_number'],
                        'expired_date' => $batch['expired_date'],
                        'stock_qty' => (int) ($batch['stock_base_unit'] ?? 0),
                        'unit_name' => $batch['unit_name'] ?? 'Tablet',
                        'buy_price' => (string) ($batch['buy_price_base'] ?? '0.00'),
                        'synced_at' => now(),
                    ]
                );
                $batchCount++;
            }
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Sinkronisasi hybrid berhasil diproses.',
            'synced_counts' => [
                'transactions' => $txCount,
                'shifts' => $shiftCount,
                'batches' => $batchCount,
            ],
            'server_timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Pull Endpoint for multi-branch catalog / updates.
     */
    public function pull(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'license_key' => ['required', 'string'],
        ]);

        $license = License::where('license_key', $validated['license_key'])->first();
        if (! $license || $license->status !== 'active') {
            return response()->json(['status' => 'error', 'message' => 'Lisensi tidak valid.'], 403);
        }

        $features = $license->features_json ?? [];
        if (empty($features['cloud_sync_pull'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Fitur Cloud Sync Pull (Katalog Terpusat Multi-Cabang) memerlukan lisensi ENTERPRISE.',
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'catalog' => [],
            'server_timestamp' => now()->toIso8601String(),
        ]);
    }
}
