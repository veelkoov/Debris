<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\Internal\EnforceStringValuesTrait;

/**
 * @extends DSet<string>
 */
class StringSet extends DSet
{
    use EnforceStringValuesTrait;

    public function join(string $separator): string
    {
        return implode($separator, $this->getValuesArray());
    }
}
