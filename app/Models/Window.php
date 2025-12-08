<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Window extends Model
{
    use HasFactory;

    protected $fillable = [
        'window_number',
        'substep1_queue_id',
        'substep2_queue_id',
        'substep3_queue_id',
        'last_queue_number'
    ];

    public function substep1Queue()
    {
        return $this->belongsTo(Queue::class, 'substep1_queue_id');
    }

    public function substep2Queue()
    {
        return $this->belongsTo(Queue::class, 'substep2_queue_id');
    }

    public function substep3Queue()
    {
        return $this->belongsTo(Queue::class, 'substep3_queue_id');
    }

    public function queues()
    {
        return $this->hasMany(Queue::class, 'window_number', 'window_number');
    }

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

    public function moveToSubstep2(): bool
    {
        if (!$this->substep1_queue_id || $this->substep2_queue_id) {
            return false;
        }

        $queue = Queue::find($this->substep1_queue_id);
        $queue->update([
            'status' => 'substep2',
            'current_substep' => 2
        ]);

        $this->update([
            'substep1_queue_id' => null,
            'substep2_queue_id' => $queue->id
        ]);

        return true;
    }

    public function moveToSubstep3(): bool
    {
        if (!$this->substep2_queue_id || $this->substep3_queue_id) {
            return false;
        }

        $queue = Queue::find($this->substep2_queue_id);
        $queue->update([
            'status' => 'substep3',
            'current_substep' => 3
        ]);

        $this->update([
            'substep2_queue_id' => null,
            'substep3_queue_id' => $queue->id
        ]);

        return true;
    }

    public function completeSubstep3(): bool
    {
        if (!$this->substep3_queue_id) {
            return false;
        }

        $queue = Queue::find($this->substep3_queue_id);
        $queue->update([
            'status' => 'completed',
            'current_substep' => null
        ]);

        $this->update(['substep3_queue_id' => null]);

        return true;
    }

    public static function getWithSubsteps(int $windowNumber)
    {
        return self::where('window_number', $windowNumber)
                   ->with(['substep1Queue', 'substep2Queue', 'substep3Queue'])
                   ->first();
    }
}
