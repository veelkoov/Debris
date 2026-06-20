<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Enforce;
use Veelkoov\Debris\Base\Map;

/**
 * @extends DStringMap<string>
 */
class StringToString extends DStringMap
{
    use Enforce\StringValuesTrait;

    public function flip(): Map
    {
        return new self(array_flip($this->items));
    }
}
