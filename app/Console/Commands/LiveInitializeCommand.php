<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Filesystem\Filesystem as SymfonyFilesystem;

#[AsCommand(name: 'live:initialize')]
class LiveInitializeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'live:initialize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate LIVE admin required database table and seed';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        $this->line('');
        $targetPath = base_path('.env');
        $filesystem = new SymfonyFilesystem();
        if (!$filesystem->exists($targetPath)) {
            $this->components->error('.env not detected!.Please rename .env.example to .env and input all required information on .env file');
            return false;
        }
        try {
            $this->info('Updating .env file...');
            //pusher variable configuring
            $this->setEnv('PUSHER_APP_ID', substr(str_shuffle('0123456789'), 0, 6));
            $this->setEnv('PUSHER_APP_KEY', substr(str_shuffle('0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ'), 0, 24));
            $this->setEnv('PUSHER_APP_SECRET', substr(str_shuffle('0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ'), 0, 24));
            $this->setEnv('PUSHER_HOST', '127.0.0.1');
            $this->setEnv('PUSHER_PORT', '6001');
            $this->setEnv('PUSHER_SCHEME', 'http');
            $this->setEnv('BROADCAST_DRIVER', 'pusher');
            //migrating and seeding database
            $this->info('Migrating database table...');
            Artisan::call('migrate --seed');
            $this->setEnv('MODULE_ACTIVATOR', 'database');
            Artisan::call('config:cache');
            Artisan::call('config:clear');
            Artisan::call('module:migrate');
            $this->info('Seeding database table...');
            Artisan::call('module:seed AppManagement');
            $this->line('');
            $this->components->info('AppManagement module seeded successfully.');
            Artisan::call('module:seed MenuManagement');
            $this->line('');
            $this->components->info('MenuManagement module seeded successfully.');
            Artisan::call('module:seed UserManagement');
            $this->line('');
            $this->components->info('UserManagement module seeded successfully.');
            Artisan::call('module:seed EmployeeManagement');
            $this->line('');
            $this->components->info('EmployeeManagement module seeded successfully.');
            Artisan::call('module:seed DataTable');
            $this->line('');
            $this->components->info('DataTable module seeded successfully.');
            $this->line('');
            $this->components->info('Database table migrated and seeded successfully.');
        } catch (\Exception $e) {
            $this->components->error('Database error:');
            dump($e->getMessage());
        }
        return true;
    }

    protected function setEnv($key, $value)
    {
        $replaced = preg_replace(
            $this->keyReplacementPattern($key, $value),
            $key . '=' . $value,
            $input = file_get_contents(app()->environmentFilePath())
        );

        file_put_contents(app()->environmentFilePath(), $replaced);

        return true;
    }

    protected function keyReplacementPattern($key)
    {
        $escaped = preg_quote('=' . env($key), '/');
        return "/^{$key}{$escaped}/m";
    }
}
