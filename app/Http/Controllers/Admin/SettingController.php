<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = [
            'app_name' => AppSetting::get('app_name', 'Laksana License Server'),
            'company_name' => AppSetting::get('company_name', 'Laksana Solution'),
            'app_tagline' => AppSetting::get('app_tagline', 'Pusat Penerbitan & Aktivasi Lisensi Ed25519'),
            'footer_text' => AppSetting::get('footer_text', '© 2026 Laksana Solution'),
            'default_reset_limit' => (int) AppSetting::get('default_reset_limit', 3),
            'default_grace_days' => (int) AppSetting::get('default_grace_days', 14),
            'default_heartbeat_days' => (int) AppSetting::get('default_heartbeat_days', 7),
            'master_api_token' => AppSetting::get('master_api_token') ?? $this->ensureApiToken(),
            'server_public_url' => AppSetting::get('server_public_url', url('/')),
        ];

        $publicKey = config('license.public_key') ?: env('LICENSE_PUBLIC_KEY', 'Belum dikonfigurasi di .env');

        return view('admin.settings.index', compact('settings', 'publicKey'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'app_tagline' => ['nullable', 'string', 'max:255'],
            'footer_text' => ['nullable', 'string', 'max:255'],
            'server_public_url' => ['nullable', 'url', 'max:255'],
            'default_reset_limit' => ['required', 'integer', 'min:0', 'max:99'],
            'default_grace_days' => ['required', 'integer', 'min:0', 'max:365'],
            'default_heartbeat_days' => ['required', 'integer', 'min:1', 'max:90'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
        ]);

        AppSetting::set('app_name', $validated['app_name'], 'general');
        AppSetting::set('company_name', $validated['company_name'], 'general');
        AppSetting::set('app_tagline', $validated['app_tagline'] ?? '', 'general');
        AppSetting::set('footer_text', $validated['footer_text'] ?? '© 2026 Laksana Solution', 'general');
        AppSetting::set('server_public_url', $validated['server_public_url'] ?? url('/'), 'connection');
        AppSetting::set('default_reset_limit', (string) $validated['default_reset_limit'], 'license');
        AppSetting::set('default_grace_days', (string) $validated['default_grace_days'], 'license');
        AppSetting::set('default_heartbeat_days', (string) $validated['default_heartbeat_days'], 'license');

        // Handle Logo Upload if provided
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $file = $request->file('logo');
            $file->move(public_path('images'), 'logo.png');
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan aplikasi dan konfigurasi lisensi berhasil diperbarui!');
    }

    public function regenerateApiToken(): RedirectResponse
    {
        $newToken = 'lsk_' . Str::random(48);
        AppSetting::set('master_api_token', $newToken, 'security');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Master API Token baru berhasil digenerate! Harap perbarui token di sistem eksternal Anda.');
    }

    private function ensureApiToken(): string
    {
        $token = 'lsk_' . Str::random(48);
        AppSetting::set('master_api_token', $token, 'security');
        return $token;
    }
}
