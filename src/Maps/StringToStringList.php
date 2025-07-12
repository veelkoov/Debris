<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Maps;

use Veelkoov\Debris\Base\DStringMap;
use Veelkoov\Debris\Base\Internal\EnforceStringListValuesTrait;
use Veelkoov\Debris\Base\Internal\EnforceStringValuesTrait;
use Veelkoov\Debris\StringList;

/**
 * @extends DStringMap<StringList>
 */
class StringToStringList extends DStringMap
{
    use EnforceStringListValuesTrait;
}
