<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Window extends Model
{
    use HasFactory;

    protected $fillable = [
        'window_number',
        'status',
    ];

    /**
     * Get all queues served by this window
     */
    public function queues()
    {
        return $this->hasMany(Queue::class);
    }

    /**
     * Get the current queue being served
     */
    public function currentQueue()
    {
        return $this->hasOne(Queue::class)->where('status', 'serving');
    }

    /**
     * Check if window is available
     */
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    /**
     * Call next queue
     */
    public function callNext()
    {
        if (!$this->isAvailable()) {
            return false;
        }

        $nextQueue = $this->queues()
                          ->where('status', 'waiting')
                          ->orderBy('created_at', 'asc')
                          ->first();

        if (!$nextQueue) {
            return false;
        }

        return $this->serveQueue($nextQueue);
    }

    /**
     * Serve a specific queue
     */
    public function serveQueue(Queue $queue)
    {
        $queue->update([
            'status' => 'serving',
        ]);

        $this->update([
            'status' => 'busy',
        ]);

        return $queue;
    }

    /**
     * Complete current service
     */
    public function completeService()
    {
        if (!$this->currentQueue) {
            return false;
        }

        $queue = Queue::find($this->currentQueue->id);

        $queue->update(['status' => 'completed']);

        $this->update([
            'status' => 'available',
        ]);

        return true;
    }
}
