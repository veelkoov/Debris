<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Enforce;

trait AnyValuesTrait
{
    #[\Override]
    protected static function enforceValueType(mixed $value): mixed
    {
        return $value;
    }

    #[\Override]
    protected static function isValidValue(mixed $value): bool
    {
        return true;
    }
}
