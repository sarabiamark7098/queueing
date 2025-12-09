<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_number',
        'status',
        'window_id'
    ];

    /**
     * Get the window serving this queue
     */

    public function window()
    {
        return $this->belongsTo(Window::class);
    }

    /**
     * Generate unique queue number
     */
    public static function generateQueueNumber(int $windowId)
    {

        // Get last queue for today
        $lastQueue = self::whereDate('created_at', now())
                         ->where('window_id', $windowId)
                         ->orderBy('id', 'desc')
                         ->first();


        $sequence = $lastQueue
            ? intval(substr($lastQueue->queue_number, -4)) + 1
            : 1;

        return 'W' . $windowId . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get waiting queues
     */
    public static function getWaiting(int $windowId)
    {
        return self::where('status', 'waiting')
                    ->where('window_id', $windowId)
                    ->orderBy('created_at', 'asc')
                    ->get();
    }

    /**
     * Get statistics
     */
    public static function getStatistics()
    {
        return [
            'waiting' => self::where('status', 'waiting')->count(),
            'serving' => self::where('status', 'serving')->count(),
            'completed' => self::where('status', 'completed')->count(),
        ];
    }
}
