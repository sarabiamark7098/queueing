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
        for ($i = 1; $i <= 4; $i++) {
            Window::create([
                'window_number' => $i,
                'substep1_queue_id' => null,
                'substep2_queue_id' => null,
                'substep3_queue_id' => null,
                'last_queue_number' => 0
            ]);
        }

        $this->command->info('✅ 4 windows created successfully!');
    }
}
