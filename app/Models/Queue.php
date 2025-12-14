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

    public static function createManualQueue(int $windowNumber, string $queueNumber, string $mode): array
    {
        // Clean the input
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

        if ($mode === 'special') {
            // Create one-time special queue number
            $queue = self::create([
                'queue_number' => $queueNumber,
                'window_number' => $windowNumber,
                'status' => 'waiting',
                'is_special' => true,
                'is_manual' => true
            ]);

            return [
                'success' => true,
                'queue' => $queue,
                'message' => 'Special queue number created'
            ];
        } else {
            // Extract prefix from queue number (everything before last dash and numbers)
            preg_match('/^(.+?)-(\d+)$/', $queueNumber, $matches);

            if (!$matches) {
                return [
                    'success' => false,
                    'error' => 'Invalid format for prefix mode. Format should be: PREFIX-0001'
                ];
            }

            $prefix = $matches[1];
            $sequence = intval($matches[2]);

            // Create the queue
            $queue = self::create([
                'queue_number' => $queueNumber,
                'window_number' => $windowNumber,
                'status' => 'waiting',
                'is_special' => false,
                'is_manual' => true
            ]);

            // Update or create sequence tracker with the current sequence
            $sequenceRecord = QueueSequence::firstOrCreate(
                ['prefix' => $prefix],
                ['last_sequence' => 0]
            );

            // Update sequence if manual number is higher
            if ($sequence > $sequenceRecord->last_sequence) {
                $sequenceRecord->update(['last_sequence' => $sequence]);
            }

            return [
                'success' => true,
                'queue' => $queue,
                'prefix' => $prefix,
                'message' => "Queue created. Prefix '{$prefix}' is now available for auto-generation"
            ];
        }
    }

    public static function generateQueueNumber(int $windowNumber): string
    {
        $window = Window::where('window_number', $windowNumber)->first();

        // Get prefix (custom or default)
        $prefix = $window->getQueuePrefix();

        // Get next sequence from global sequence tracker
        $sequence = QueueSequence::getNextSequence($prefix);

        // Format: CustomPrefix-0001 or W1-0001
        $queueNumber = $prefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);

        // Double-check uniqueness (extra safety)
        while (self::where('queue_number', $queueNumber)->exists()) {
            $sequence = QueueSequence::getNextSequence($prefix);
            $queueNumber = $prefix . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        }

        return $queueNumber;
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
