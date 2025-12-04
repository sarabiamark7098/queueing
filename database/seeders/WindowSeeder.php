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
        // Create 4 windows
        for ($i = 1; $i <= 4; $i++) {
            Window::create([
                'window_number' => $i,
                'status' => 'available',
            ]);
        }

        $this->command->info('4 windows created successfully!');
    }
}
