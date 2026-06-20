<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Enforce;

use Veelkoov\Debris\Vecs\StringVec;

trait StringVecValuesTrait
{
    #[\Override]
    protected static function enforceValueType(mixed $value): StringVec
    {
        return $value;
    }

    #[\Override]
    protected static function isValidValue(mixed $value): bool
    {
        return $value instanceof StringVec;
    }
}
