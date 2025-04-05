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
}
