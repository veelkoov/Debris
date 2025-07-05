<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

trait EnforceBoolValuesTrait
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
