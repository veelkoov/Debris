<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Enforce;

trait StringKeysTrait
{
    #[\Override]
    protected static function enforceKeyType(mixed $key): string
    {
        return $key;
    }

    #[\Override]
    protected static function isValidKey(mixed $value): bool
    {
        return \is_string($value);
    }
}
