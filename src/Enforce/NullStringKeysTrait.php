<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Enforce;

trait NullStringKeysTrait
{
    #[\Override]
    protected static function enforceKeyType(mixed $value): ?string
    {
        return $value;
    }

    #[\Override]
    protected static function isValidKey(mixed $value): bool
    {
        return null === $value || \is_string($value);
    }
}
