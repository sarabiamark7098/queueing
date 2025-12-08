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

    public function window()
    {
        return $this->belongsTo(Window::class, 'window_number', 'window_number');
    }

    public static function generateQueueNumber(int $windowNumber): string
    {
        $window = Window::where('window_number', $windowNumber)->first();

        $sequence = $window->last_queue_number + 1;

        $window->update(['last_queue_number' => $sequence]);

        return 'W' . $windowNumber . '-' . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }

    public static function getWaitingForWindow(int $windowNumber)
    {
        return self::where('window_number', $windowNumber)
                   ->where('status', 'waiting')
                   ->orderBy('created_at', 'asc')
                   ->get();
    }

    public static function getWindowStatistics(int $windowNumber): array
    {
        return [
            'waiting' => self::where('window_number', $windowNumber)
                            ->where('status', 'waiting')
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
