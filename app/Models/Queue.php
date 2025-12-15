<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_number',
        'window_number',
        'status',
        'current_substep',
        'is_special',
        'is_manual'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function window()
    {
        return $this->belongsTo(Window::class, 'window_number', 'window_number');
    }

    public static function generateQueueNumber(int $windowNumber): string
    {
        $window = Window::where('window_number', $windowNumber)->first();

        // Get next sequence number for this window
        $sequence = $window->last_queue_number + 1;

        // Update window's last queue number
        $window->update(['last_queue_number' => $sequence]);

        // Format: W1-0001, W2-0001, etc.
        $queueNumber = 'W' . $windowNumber . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        // Double-check uniqueness
        while (self::where('queue_number', $queueNumber)->exists()) {
            $sequence++;
            $window->update(['last_queue_number' => $sequence]);
            $queueNumber = 'W' . $windowNumber . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        }

        return $queueNumber;
    }

    public static function createManualQueue(int $windowNumber, string $queueNumber): array
    {
        // Clean and uppercase the input
        $queueNumber = strtoupper(trim($queueNumber));

        // Check if queue number already exists
        if (self::where('queue_number', $queueNumber)->exists()) {
            return [
                'success' => false,
                'error' => "Queue number '{$queueNumber}' already exists"
            ];
        }

        // Validate format (allow letters, numbers, hyphens)
        if (!preg_match('/^[A-Z0-9\-]+$/', $queueNumber)) {
            return [
                'success' => false,
                'error' => 'Invalid format. Use only letters, numbers, and hyphens'
            ];
        }

        // Create the manual queue
        $queue = self::create([
            'queue_number' => $queueNumber,
            'window_number' => $windowNumber,
            'status' => 'waiting',
            'is_manual' => true
        ]);

        return [
            'success' => true,
            'queue' => $queue,
            'message' => 'Manual queue created successfully'
        ];
    }

    public static function getWaitingForWindow(int $windowNumber)
    {
        return self::where('window_number', $windowNumber)
                   ->where('status', 'waiting')
                   ->orderBy('created_at', 'asc')
                   ->get();
    }

    public static function getWaitingForSubstep2(int $windowNumber)
    {
        return self::where('window_number', $windowNumber)
                ->where('status', 'waiting_substep2')
                ->orderBy('created_at', 'asc')
                ->get();
    }

    public static function getWaitingForSubstep3(int $windowNumber)
    {
        return self::where('window_number', $windowNumber)
                ->where('status', 'waiting_substep3')
                ->orderBy('created_at', 'asc')
                ->get();
    }

        public static function getWindowStatistics(int $windowNumber): array
    {
        return [
            'waiting' => self::where('window_number', $windowNumber)
                            ->where('status', 'waiting')
                            ->count(),
            'waiting_substep2' => self::where('window_number', $windowNumber)
                                    ->where('status', 'waiting_substep2')
                                    ->count(),
            'waiting_substep3' => self::where('window_number', $windowNumber)
                                    ->where('status', 'waiting_substep3')
                                    ->count(),
            'serving' => self::where('window_number', $windowNumber)
                            ->whereIn('status', ['substep1', 'substep2', 'substep3'])
                            ->count(),
            'completed' => self::where('window_number', $windowNumber)
                            ->where('status', 'completed')
                            ->count(),
        ];
    }


    public static function getOverallStatistics(): array
    {
        return [
            'waiting' => self::where('status', 'waiting')->count(),
            'serving' => self::whereIn('status', ['substep1', 'substep2', 'substep3'])->count(),
            'completed' => self::where('status', 'completed')->count(),
        ];
    }

    public static function getRecentQueues(int $limit = 10)
    {
        return self::orderBy('created_at', 'desc')
                   ->limit($limit)
                   ->get();
    }
}
