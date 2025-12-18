<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Window;

class WindowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $windows = [
            ['window_number' => 1, 'prefix' => 'COS'],
            ['window_number' => 2, 'prefix' => 'COS'],
            ['window_number' => 3, 'prefix' => 'COS'],
            ['window_number' => 4, 'prefix' => 'JO'],
        ];

        foreach ($windows as $window) {
            Window::updateOrCreate(
                ['window_number' => $window['window_number']],
                [
                    'prefix' => $window['prefix'],
                    'substep1_queue_id' => null,
                    'substep2_queue_id' => null,
                    'substep3_queue_id' => null,
                    'last_queue_number' => 0,
                    'last_reset_date' => now()->toDateString()
                ]
            );
        }

        $this->command->info('✅ 4 windows created with prefixes!');
        $this->command->info('   - Windows 1, 2, 3: COS prefix');
        $this->command->info('   - Window 4: JO prefix');
    }
}
