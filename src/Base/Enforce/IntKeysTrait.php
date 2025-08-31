<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Enforce;

trait IntKeysTrait
{
    #[\Override]
    protected static function enforceKeyType(mixed $key): int
    {
        return $key;
    }

    #[\Override]
    protected static function isValidKey(mixed $value): bool
    {
        return \is_int($value);
    }
}
