<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\Licensing\LicenseSignerService;
use Illuminate\Console\Command;

class GenerateLicenseKeysCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'license:generate-keys {--show : Only display the keys without modifying .env}';

    /**
     * The console command description.
     */
    protected $description = 'Generate Ed25519 keypair for offline license signing';

    /**
     * Execute the console command.
     */
    public function handle(LicenseSignerService $signer): int
    {
        $keys = $signer->generateKeypair();

        $this->info('Ed25519 Keypair generated successfully!');
        $this->line('');
        $this->comment('LICENSE_PUBLIC_KEY:');
        $this->line($keys['public_key']);
        $this->line('');
        $this->comment('LICENSE_PRIVATE_KEY (SECRET! KEEP ONLY ON CLOUD HUB):');
        $this->line($keys['private_key']);
        $this->line('');

        if ($this->option('show')) {
            return self::SUCCESS;
        }

        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);

            if (str_contains($envContent, 'LICENSE_PRIVATE_KEY=')) {
                $envContent = preg_replace(
                    '/LICENSE_PRIVATE_KEY=.*/',
                    'LICENSE_PRIVATE_KEY='.$keys['private_key'],
                    $envContent
                );
            } else {
                $envContent .= "\nLICENSE_PRIVATE_KEY=".$keys['private_key'];
            }

            if (str_contains($envContent, 'LICENSE_PUBLIC_KEY=')) {
                $envContent = preg_replace(
                    '/LICENSE_PUBLIC_KEY=.*/',
                    'LICENSE_PUBLIC_KEY='.$keys['public_key'],
                    $envContent
                );
            } else {
                $envContent .= "\nLICENSE_PUBLIC_KEY=".$keys['public_key'];
            }

            file_put_contents($envPath, $envContent);
            $this->info('Updated .env file with new keypair.');
        }

        return self::SUCCESS;
    }
}
