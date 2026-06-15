<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Sets;

use Veelkoov\Debris\Base\DSet;
use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Enforce;
use Veelkoov\Debris\Base\Map;
use Veelkoov\Debris\Collections\Strings;

/**
 * @extends DSet<string>
 */
class StringSet extends DSet implements Strings
{
    use Enforce\StringValuesTrait;

    public function join(string $separator): string
    {
        return implode($separator, $this->getValuesArray());
    }

    protected static function getNewInternalContainer(): Map
    {
        return new DStringMap();
    }
}
