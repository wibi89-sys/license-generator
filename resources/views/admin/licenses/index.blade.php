<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Hub — Manajemen Lisensi</title>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; background: #f8fafc; color: #1e293b; margin: 0; padding: 24px; }
        .container { max-width: 1200px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .btn { background: #2563eb; color: white; padding: 8px 16px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 14px; border: none; cursor: pointer; }
        .btn-danger { background: #ef4444; }
        .btn:hover { opacity: 0.9; }
        table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f1f5f9; font-weight: 600; color: #475569; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 9999px; font-size: 12px; font-weight: 600; }
        .badge-active { background: #dcfce7; color: #166534; }
        .badge-standard { background: #e0e7ff; color: #3730a3; }
        .badge-pro { background: #fef3c7; color: #92400e; }
        .badge-enterprise { background: #f3e8ff; color: #6b21a8; }
        .alert { padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; font-size: 14px; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #86efac; }
        .alert-error { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
        .hwid { font-family: monospace; font-size: 11px; color: #64748b; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div>
            <h1 style="margin: 0; font-size: 24px;">Cloud Hub — Server Lisensi Apotek</h1>
            <p style="margin: 4px 0 0; color: #64748b; font-size: 14px;">Penerbitan & Kontrol Hak Akses Lisensi Offline Ed25519</p>
        </div>
        <div style="display: flex; gap: 8px; align-items: center;">
            <a href="{{ route('admin.dashboard') }}" class="btn" style="background: #475569;">📊 Dashboard</a>
            <a href="{{ route('admin.licenses.create') }}" class="btn">+ Terbitkan Lisensi Baru</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline; margin: 0;">
                @csrf
                <button type="submit" class="btn btn-danger" style="cursor: pointer;">Keluar</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Kunci Lisensi</th>
                <th>Nama Apotek / Cabang</th>
                <th>Paket (Tier)</th>
                <th>Bound HWID</th>
                <th>Reset HWID</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @forelse($licenses as $lic)
            <tr>
                <td><strong>{{ $lic->license_key }}</strong></td>
                <td>
                    <div>{{ $lic->pharmacy_name }} ({{ $lic->customer_name }})</div>
                    <small style="color: #64748b;">Cabang: {{ $lic->branch_code }}</small>
                </td>
                <td>
                    <span class="badge badge-{{ strtolower(explode('_', $lic->plan_type->value)[0]) }}">
                        {{ $lic->plan_type->value }}
                    </span>
                    <small style="display:block; color: #64748b;">{{ $lic->billing_type }}</small>
                </td>
                <td>
                    @if($lic->bound_hwid)
                        <span class="hwid">{{ substr($lic->bound_hwid, 0, 16) }}...</span>
                        <div style="font-size: 11px; color: #64748b;">Host: {{ $lic->bound_hostname ?? '-' }}</div>
                    @else
                        <em style="color: #94a3b8;">Belum terikat</em>
                    @endif
                </td>
                <td>
                    {{ $lic->reset_used }} / {{ $lic->max_reset_count }}
                </td>
                <td>
                    <span class="badge badge-active">{{ ucfirst($lic->status) }}</span>
                </td>
                <td>
                    @if($lic->bound_hwid)
                        <form method="POST" action="{{ route('admin.licenses.reset-hwid', $lic) }}" onsubmit="return confirm('Yakin ingin mereset HWID lisensi ini?');">
                            @csrf
                            <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 12px;" {{ !$lic->canResetHwid() ? 'disabled' : '' }}>
                                Reset HWID
                            </button>
                        </form>
                    @else
                        <span style="color: #94a3b8; font-size: 12px;">Siap Aktivasi</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align: center; color: #94a3b8; padding: 32px;">Belum ada lisensi yang diterbitkan.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
