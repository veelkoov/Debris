<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

trait EnforceIntValuesTrait
{
    #[\Override]
    protected static function enforceValueType(mixed $value): int
    {
        return $value;
    }
}
