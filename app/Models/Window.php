<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Window extends Model
{
    use HasFactory;

    protected $fillable = [
        'window_number',
        'custom_prefix',
        'use_custom_prefix',
        'substep1_queue_id',
        'substep2_queue_id',
        'substep3_queue_id'
    ];

    public function getQueuePrefix(): string
    {
        if ($this->use_custom_prefix && $this->custom_prefix) {
            return $this->custom_prefix;
        }

        return 'W' . $this->window_number;
    }

    public function updateCustomPrefix(?string $prefix): array
    {
        if ($prefix && trim($prefix) !== '') {
            $cleanPrefix = trim($prefix);

            // Check if prefix is already used by another window
            $conflictWindow = Window::where('custom_prefix', $cleanPrefix)
                                ->where('use_custom_prefix', true)
                                ->where('window_number', '!=', $this->window_number)
                                ->first();

            if ($conflictWindow) {
                return [
                    'success' => false,
                    'error' => "Prefix '{$cleanPrefix}' is already used by Window {$conflictWindow->window_number}",
                    'conflict_window' => $conflictWindow->window_number
                ];
            }

            $this->update([
                'custom_prefix' => $cleanPrefix,
                'use_custom_prefix' => true
            ]);

            return [
                'success' => true,
                'message' => 'Prefix updated successfully'
            ];
        } else {
            // Reset to default
            $this->update([
                'custom_prefix' => null,
                'use_custom_prefix' => false
            ]);

            return [
                'success' => true,
                'message' => 'Prefix reset to default'
            ];
        }
    }

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
