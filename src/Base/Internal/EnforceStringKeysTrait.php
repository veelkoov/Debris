<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

trait EnforceStringKeysTrait
{
    #[\Override]
    protected static function enforceKeyType(mixed $key): string
    {
        return $key;
    }
}
