<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Enforce;

trait IntKeysTrait
{
    /**
     * @param int $key
     */
    protected static function enforceKeyType(mixed $key): int
    {
        return $key;
    }

    /**
     * @phpstan-assert-if-true int $key
     */
    protected static function isValidKey(mixed $key): bool
    {
        return \is_int($key);
    }
}
