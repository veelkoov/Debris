<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

/**
 * @extends DScalarSet<string>
 */
class StringSet extends DScalarSet
{
    public function join(string $separator): string
    {
        return implode($separator, $this->toArray());
    }
}
