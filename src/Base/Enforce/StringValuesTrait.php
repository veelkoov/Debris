<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Enforce;

trait StringValuesTrait
{
    #[\Override]
    protected static function enforceValueType(mixed $value): string
    {
        return $value;
    }

    #[\Override]
    protected static function isValidValue(mixed $value): bool
    {
        return \is_string($value);
    }
}
