<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Window;

class ResetDailyQueues extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'queues:reset-daily';

    /**
     * The console command description.
     */
    protected $description = 'Reset queue sequences for all windows at the end of the day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();

        Window::all()->each(function ($window) use ($today) {
            $window->update([
                'last_queue_number' => 0,
                'last_reset_date' => $today
            ]);
        });

        $this->info('✅ All window queue sequences have been reset for ' . $today);

        return 0;
    }
}
