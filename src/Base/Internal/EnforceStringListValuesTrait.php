<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

use Veelkoov\Debris\StringList;

trait EnforceStringListValuesTrait
{
    #[\Override]
    protected static function enforceValueType(mixed $value): StringList
    {
        return $value;
    }

    #[\Override]
    protected static function isValidValue(mixed $value): bool
    {
        return $value instanceof StringList;
    }
}
