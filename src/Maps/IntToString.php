<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DIntMap;
use Veelkoov\Debris\Base\Enforce;

/**
 * @extends DIntMap<string>
 */
class IntToString extends DIntMap
{
    use Enforce\StringValuesTrait;

    public function flip(): StringToInt
    {
        return new StringToInt(array_flip($this->items));
    }
}
