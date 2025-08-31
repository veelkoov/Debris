<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Enforce;

trait BoolKeysTrait
{
    #[\Override]
    protected static function enforceKeyType(mixed $value): bool
    {
        return $value;
    }

    #[\Override]
    protected static function isValidKey(mixed $value): bool
    {
        return \is_bool($value);
    }
}
