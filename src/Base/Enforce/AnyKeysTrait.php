<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Enforce;

trait AnyKeysTrait
{
    #[\Override]
    protected static function enforceKeyType(mixed $value): mixed
    {
        return $value;
    }

    #[\Override]
    protected static function isValidKey(mixed $value): bool
    {
        return true;
    }
}
