<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Sets;

use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Enforce;

/**
 * @extends DSet<string>
 */
class StringSet extends DSet
{
    use Enforce\StringValuesTrait;

    public function join(string $separator): string
    {
        return implode($separator, $this->getValuesArray());
    }
}
