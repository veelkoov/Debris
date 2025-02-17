<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

trait EnforceIntKeysTrait
{
    #[\Override]
    protected static function enforceKeyType(mixed $key): int
    {
        return $key;
    }
}
