<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Maps\Base\DStringMap;

/**
 * @extends DStringMap<string>
 */
class StringToString extends DStringMap
{
    use Enforce\StringValuesTrait;

    #[\Override]
    public function flip(): self
    {
        return new self(array_flip($this->items));
    }
}
