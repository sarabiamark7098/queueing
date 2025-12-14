<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QueueSequence extends Model
{
    use HasFactory;

    protected $fillable = [
        'prefix',
        'last_sequence'
    ];

    /**
     * Get next sequence number for a prefix
     */
    public static function getNextSequence(string $prefix): int
    {
        $sequence = self::firstOrCreate(
            ['prefix' => $prefix],
            ['last_sequence' => 0]
        );

        $nextSequence = $sequence->last_sequence + 1;

        $sequence->update(['last_sequence' => $nextSequence]);

        return $nextSequence;
    }

    /**
     * Check if prefix is used by other windows
     */
    public static function isPrefixInUse(string $prefix, ?int $excludeWindowNumber = null): bool
    {
        $query = Window::where('custom_prefix', $prefix)
                      ->where('use_custom_prefix', true);

        if ($excludeWindowNumber) {
            $query->where('window_number', '!=', $excludeWindowNumber);
        }

        return $query->exists();
    }

    /**
     * Get all prefixes in use
     */
    public static function getAllPrefixesInUse(): array
    {
        $customPrefixes = Window::where('use_custom_prefix', true)
                               ->whereNotNull('custom_prefix')
                               ->pluck('custom_prefix', 'window_number')
                               ->toArray();

        $defaultPrefixes = Window::where('use_custom_prefix', false)
                                ->get()
                                ->pluck('window_number')
                                ->mapWithKeys(fn($num) => [$num => 'W' . $num])
                                ->toArray();

        return array_merge($defaultPrefixes, $customPrefixes);
    }
}
