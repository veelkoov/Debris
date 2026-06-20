<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Enforce;
use Veelkoov\Debris\Base\Map;

/**
 * @extends DStringMap<int>
 */
class StringToInt extends DStringMap
{
    use Enforce\IntValuesTrait;

    public function flip(): Map
    {
        return new IntToString(array_flip($this->items));
    }
}
