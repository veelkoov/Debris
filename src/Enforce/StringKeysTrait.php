<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Enforce;

trait StringKeysTrait
{
    /**
     * @param string $key
     */
    protected static function enforceKeyType(mixed $key): string
    {
        return $key;
    }

    /**
     * @phpstan-assert-if-true string $key
     */
    protected static function isValidKey(mixed $key): bool
    {
        return \is_string($key);
    }
}
