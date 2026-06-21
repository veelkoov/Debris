<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Enforce;

trait BoolValuesTrait
{
    #[\Override]
    protected static function enforceValueType(mixed $value): bool
    {
        return $value;
    }

    #[\Override]
    protected static function isValidValue(mixed $value): bool
    {
        return \is_bool($value);
    }
}
