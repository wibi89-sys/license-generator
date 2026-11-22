<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Hub — Buat Lisensi Baru</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; color: #1e293b; margin: 0; padding: 24px; }
        .container { max-width: 650px; margin: 0 auto; background: white; padding: 24px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px; }
        input[type="text"], input[type="number"], select { width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; }
        .btn { background: #2563eb; color: white; padding: 10px 20px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer; }
        .btn-secondary { background: #94a3b8; text-decoration: none; padding: 10px 16px; border-radius: 6px; color: white; margin-right: 8px; }
        .features-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; background: #f8fafc; padding: 12px; border-radius: 6px; }
        .checkbox-label { display: flex; align-items: center; gap: 8px; font-weight: normal; font-size: 13px; }
    </style>
</head>
<body>
<div class="container">
    <h2 style="margin-top: 0;">Terbitkan Lisensi Baru</h2>

    <form method="POST" action="{{ route('admin.licenses.store') }}">
        @csrf
        <div class="form-group">
            <label>Nama Pemilik / Customer:</label>
            <input type="text" name="customer_name" required placeholder="Contoh: Apt. Budi Santoso, S.Farm">
        </div>
        <div class="form-group">
            <label>Nama Apotek:</label>
            <input type="text" name="pharmacy_name" required placeholder="Contoh: Apotek Sehat Sentosa">
        </div>
        <div class="form-group">
            <label>Kode Cabang:</label>
            <input type="text" name="branch_code" value="BR01" required>
        </div>
        <div class="form-group">
            <label>Paket (Tier Entitlement):</label>
            <select name="plan_type" id="plan_type" onchange="updateDefaults()">
                <option value="STANDARD_LOCAL">STANDARD_LOCAL (Lifetime, POS Inti Offline, 2 Terminal)</option>
                <option value="PRO_CLOUD_SYNC">PRO_CLOUD_SYNC (Langganan, Sync Push, Fingerprint, Analytics)</option>
                <option value="ENTERPRISE">ENTERPRISE (Multi-Cabang, Sync Pull, API Eksternal)</option>
            </select>
        </div>
        <div class="form-group">
            <label>Tipe Penagihan:</label>
            <select name="billing_type" id="billing_type">
                <option value="lifetime">Lifetime</option>
                <option value="subscription">Subscription (Tahunan)</option>
            </select>
        </div>
        <div class="form-group">
            <label>Batas Reset HWID:</label>
            <input type="number" name="max_reset_count" value="2" min="0" required>
        </div>

        <div class="form-group">
            <label>Fitur Tambahan (Override Add-on):</label>
            <div class="features-grid">
                @foreach($features as $f)
                    <label class="checkbox-label">
                        <input type="checkbox" name="features[]" value="{{ $f->value }}" class="feature-cb" id="f_{{ $f->value }}">
                        {{ $f->value }}
                    </label>
                @endforeach
            </div>
        </div>

        <div style="margin-top: 24px; display: flex; justify-content: flex-end; align-items: center;">
            <a href="{{ route('admin.licenses.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn">Simpan & Terbitkan Lisensi</button>
        </div>
    </form>
</div>

<script>
    function updateDefaults() {
        const plan = document.getElementById('plan_type').value;
        const billing = document.getElementById('billing_type');
        const cbs = document.querySelectorAll('.feature-cb');

        if (plan === 'STANDARD_LOCAL') {
            billing.value = 'lifetime';
            cbs.forEach(cb => cb.checked = false);
        } else if (plan === 'PRO_CLOUD_SYNC') {
            billing.value = 'subscription';
            const proFeatures = ['fingerprint_auth', 'label_printing', 'offsite_backup', 'cloud_sync_push', 'sipnap_export', 'profit_analytics'];
            cbs.forEach(cb => cb.checked = proFeatures.includes(cb.value));
        } else if (plan === 'ENTERPRISE') {
            billing.value = 'subscription';
            cbs.forEach(cb => cb.checked = true);
        }
    }
    updateDefaults();
</script>
</body>
</html>
