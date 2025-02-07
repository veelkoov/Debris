<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Base\DSet;

/**
 * @extends DSet<string>
 */
class StringSet extends DSet
{
    public function join(string $separator): string
    {
        return implode($separator, $this->getValuesArray());
    }
}
