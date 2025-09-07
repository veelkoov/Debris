<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Enforce;

trait NullStringValuesTrait
{
    #[\Override]
    protected static function enforceValueType(mixed $value): ?string
    {
        return $value;
    }

    #[\Override]
    protected static function isValidValue(mixed $value): bool
    {
        return null === $value || \is_string($value);
    }
}
