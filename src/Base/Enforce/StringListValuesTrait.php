<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Enforce;

use Veelkoov\Debris\Lists\StringList;

trait StringListValuesTrait
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
