<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Enforce;
use Veelkoov\Debris\Lists\StringList;

/**
 * @extends DStringMap<StringList>
 */
class StringToStringList extends DStringMap
{
    use Enforce\StringListValuesTrait;
}
