<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class MigrateFreshSeedDaily extends Command
{
    protected $signature = 'db:migrate-fresh-seed-daily';
    protected $description = 'Run migrate:fresh --seed daily';

    public function handle()
    {
        // Prevent accidental production wipe
        if (! app()->environment(['local', 'testing', 'staging'])) {
            $this->error('This command is not allowed in production.');
            return Command::FAILURE;
        }

        Artisan::call('migrate:fresh', [
            '--seed'  => true,
            '--force' => true,
        ]);

        $this->info('Database migrated fresh and seeded successfully.');
        return Command::SUCCESS;
    }
}
