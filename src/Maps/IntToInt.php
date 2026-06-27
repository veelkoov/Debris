<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Maps\Base\DIntMap;

/**
 * @extends DIntMap<int>
 */
class IntToInt extends DIntMap
{
    use Enforce\IntValuesTrait;

    #[\Override]
    public function flip(): self
    {
        return new self(array_flip($this->items));
    }
}
