<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Sets;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Map;
use Veelkoov\Debris\Maps\Base\DStringMap;
use Veelkoov\Debris\Sets\Base\DSet;
use Veelkoov\Debris\Strings;

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
