<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

trait EnforceStringValuesTrait
{
    #[\Override]
    protected static function enforceValueType(mixed $value): string
    {
        return $value;
    }
}
