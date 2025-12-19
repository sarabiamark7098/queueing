<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Window extends Model
{
    use HasFactory;

    protected $fillable = [
        'window_number',
        'prefix',
        'substep1_queue_id',
        'substep2_queue_id',
        'substep3_queue_id',
        'last_queue_number',
        'last_reset_date'
    ];

    protected $casts = [
        'last_reset_date' => 'date'
    ];

    /**
     * Get queue in substep 1
     */
    public function substep1Queue()
    {
        return $this->belongsTo(Queue::class, 'substep1_queue_id');
    }

    /**
     * Get queue in substep 2
     */
    public function substep2Queue()
    {
        return $this->belongsTo(Queue::class, 'substep2_queue_id');
    }

    /**
     * Get queue in substep 3
     */
    public function substep3Queue()
    {
        return $this->belongsTo(Queue::class, 'substep3_queue_id');
    }

    /**
     * Get all queues for this window
     */
    public function queues()
    {
        return $this->hasMany(Queue::class, 'window_number', 'window_number');
    }

    /**
     * Check if sequence needs daily reset
     */
    public function checkAndResetDaily(): void
    {
        $today = now()->toDateString();
        $lastResetDate = $this->last_reset_date->toDateString();
        
        if ($lastResetDate !== $today) {
            $this->update([
                'last_queue_number' => 0,
                'last_reset_date' => $today
            ]);
        }
    }

    /**
     * Get next sequence number for this window
     */
    public function getNextSequence(): int
    {
        $this->checkAndResetDaily();

        $nextSequence = $this->last_queue_number + 1;
        $this->update(['last_queue_number' => $nextSequence]);

        return $nextSequence;
    }

    /**
     * Get queue number format preview
     */
    public function getQueueFormat(): string
    {
        return $this->prefix . '-001';
    }

    /**
     * Call next queue to substep 1
     */
    public function callNextToSubstep1(): ?Queue
    {
        if ($this->substep1_queue_id) {
            return null;
        }

        $nextQueue = Queue::where('window_number', $this->window_number)
                         ->where('status', 'waiting')
                         ->orderBy('created_at', 'asc')
                         ->first();

        if (!$nextQueue) {
            return null;
        }

        $nextQueue->update([
            'status' => 'substep1',
            'current_substep' => 1
        ]);

        $this->update(['substep1_queue_id' => $nextQueue->id]);

        return $nextQueue;
    }

    /**
     * Call specific queue to substep 1
     */
    public function callSpecificToSubstep1(int $queueId): ?Queue
    {
        if ($this->substep1_queue_id) {
            return null;
        }

        $queue = Queue::where('id', $queueId)
                     ->where('window_number', $this->window_number)
                     ->where('status', 'waiting')
                     ->first();

        if (!$queue) {
            return null;
        }

        $queue->update([
            'status' => 'substep1',
            'current_substep' => 1
        ]);

        $this->update(['substep1_queue_id' => $queue->id]);

        return $queue;
    }

    /**
     * Move from substep 1 to waiting for substep 2
     */
    public function moveToSubstep2(): bool
    {
        if (!$this->substep1_queue_id) {
            return false;
        }

        $queue = Queue::find($this->substep1_queue_id);

        $queue->update([
            'status' => 'waiting_substep2',
            'current_substep' => null
        ]);

        $this->update(['substep1_queue_id' => null]);

        return true;
    }

    /**
     * Call next queue to substep 2
     */
    public function callNextToSubstep2(): ?Queue
    {
        if ($this->substep2_queue_id) {
            return null;
        }

        $nextQueue = Queue::where('window_number', $this->window_number)
                         ->where('status', 'waiting_substep2')
                         ->orderBy('created_at', 'asc')
                         ->first();

        if (!$nextQueue) {
            return null;
        }

        $nextQueue->update([
            'status' => 'substep2',
            'current_substep' => 2
        ]);

        $this->update(['substep2_queue_id' => $nextQueue->id]);

        return $nextQueue;
    }

    /**
     * Call specific queue to substep 2
     */
    public function callSpecificToSubstep2(int $queueId): ?Queue
    {
        if ($this->substep2_queue_id) {
            return null;
        }

        $queue = Queue::where('id', $queueId)
                     ->where('window_number', $this->window_number)
                     ->where('status', 'waiting_substep2')
                     ->first();

        if (!$queue) {
            return null;
        }

        $queue->update([
            'status' => 'substep2',
            'current_substep' => 2
        ]);

        $this->update(['substep2_queue_id' => $queue->id]);

        return $queue;
    }

    /**
     * Move from substep 2 to waiting for substep 3
     */
    public function moveToSubstep3(): bool
    {
        if (!$this->substep2_queue_id) {
            return false;
        }

        $queue = Queue::find($this->substep2_queue_id);

        $queue->update([
            'status' => 'waiting_substep3',
            'current_substep' => null
        ]);

        $this->update(['substep2_queue_id' => null]);

        return true;
    }

    /**
     * Call next queue to substep 3
     */
    public function callNextToSubstep3(): ?Queue
    {
        if ($this->substep3_queue_id) {
            return null;
        }

        $nextQueue = Queue::where('window_number', $this->window_number)
                         ->where('status', 'waiting_substep3')
                         ->orderBy('created_at', 'asc')
                         ->first();

        if (!$nextQueue) {
            return null;
        }

        $nextQueue->update([
            'status' => 'substep3',
            'current_substep' => 3
        ]);

        $this->update(['substep3_queue_id' => $nextQueue->id]);

        return $nextQueue;
    }

    /**
     * Complete substep 3
     */
    public function completeSubstep3(): bool
    {
        if (!$this->substep3_queue_id) {
            return false;
        }

        $queue = Queue::find($this->substep3_queue_id);
        $queue->update(['status' => 'completed']);

        $this->update(['substep3_queue_id' => null]);

        return true;
    }

    /**
     * Get window with all substeps loaded
     */
    public static function getWithSubsteps(int $windowNumber)
    {
        return self::where('window_number', $windowNumber)
                   ->with(['substep1Queue', 'substep2Queue', 'substep3Queue'])
                   ->first();
    }
}
