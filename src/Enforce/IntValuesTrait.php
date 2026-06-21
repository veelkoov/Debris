<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Enforce;

trait IntValuesTrait
{
    #[\Override]
    protected static function enforceValueType(mixed $value): int
    {
        return $value;
    }

    #[\Override]
    protected static function isValidValue(mixed $value): bool
    {
        return \is_int($value);
    }
}
