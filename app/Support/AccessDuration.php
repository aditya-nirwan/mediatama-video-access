<?php

namespace App\Support;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use InvalidArgumentException;

final class AccessDuration
{
    public const OPTIONS = [
        '5m' => ['label' => '5 menit', 'minutes' => 5],
        '1h' => ['label' => '1 jam', 'minutes' => 60],
        '6h' => ['label' => '6 jam', 'minutes' => 360],
        '1d' => ['label' => '1 hari', 'minutes' => 1440],
        '3d' => ['label' => '3 hari', 'minutes' => 4320],
        '7d' => ['label' => '7 hari', 'minutes' => 10080],
    ];

    public static function keys(): array
    {
        return array_keys(self::OPTIONS);
    }

    public static function minutes(string $key): int
    {
        if (! isset(self::OPTIONS[$key])) {
            throw new InvalidArgumentException("Durasi {$key} tidak dikenal.");
        }

        return self::OPTIONS[$key]['minutes'];
    }

    public static function resolve(string $key, ?string $customDateTime = null): CarbonInterface
    {
        if ($key === 'custom') {
            return Carbon::parse($customDateTime);
        }

        return now()->addMinutes(self::minutes($key));
    }
}