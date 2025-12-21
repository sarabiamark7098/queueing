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
        'current_substep'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the window this queue belongs to
     */
    public function window()
    {
        return $this->belongsTo(Window::class, 'window_number', 'window_number');
    }

    /**
     * Generate automatic queue number for a window
     */
    public static function generateQueueNumber(int $windowNumber): string
    {
        $window = Window::where('window_number', $windowNumber)->first();

        $sequence = $window->getNextSequence();

        $queueNumber = $window->prefix . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
        
        while (self::where('queue_number', $queueNumber)
                ->where('window_number', $windowNumber)
                ->whereDate('created_at', now()->toDateString())
                ->exists()) {
            $sequence = $window->getNextSequence();
            $queueNumber = $window->prefix . '-' . str_pad($sequence, 3, '0', STR_PAD_LEFT);
        }

        return $queueNumber;
    }

    /**
     * Get waiting queues for a window
     */
    public static function getWaitingForWindow(int $windowNumber)
    {
        return self::where('window_number', $windowNumber)
                   ->where('status', 'waiting')
                   ->orderBy('created_at', 'asc')
                   ->get();
    }

    /**
     * Get queues waiting for substep 2
     */
    public static function getWaitingForSubstep2(int $windowNumber)
    {
        return self::where('window_number', $windowNumber)
                   ->where('status', 'waiting_substep2')
                   ->orderBy('created_at', 'asc')
                   ->get();
    }

    /**
     * Get queues waiting for substep 3
     */
    public static function getWaitingForSubstep3(int $windowNumber)
    {
        return self::where('window_number', $windowNumber)
                   ->where('status', 'waiting_substep3')
                   ->orderBy('created_at', 'asc')
                   ->get();
    }

    /**
     * Get statistics for a window
     */
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

    /**
     * Get overall statistics
     */
    public static function getOverallStatistics(): array
    {
        return [
            'waiting' => self::where('status', 'waiting')->count(),
            'serving' => self::whereIn('status', ['substep1', 'substep2', 'substep3'])->count(),
            'completed' => self::where('status', 'completed')->count(),
        ];
    }

    /**
     * Get recent queues from all windows
     */
    public static function getRecentQueues(int $limit = 10)
    {
        return self::orderBy('created_at', 'desc')
                   ->limit($limit)
                   ->get();
    }
}
