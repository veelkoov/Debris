<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DIntMap;
use Veelkoov\Debris\Base\Enforce;
use Veelkoov\Debris\Base\Map;

/**
 * @extends DIntMap<int>
 */
class IntToInt extends DIntMap
{
    use Enforce\IntValuesTrait;

    public function flip(): self
    {
        return new self(array_flip($this->items));
    }
}
