<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Sets;

use Veelkoov\Debris\Enforce;
use Veelkoov\Debris\Sets\Base\DIntOrStringSet;
use Veelkoov\Debris\Strings;

/**
 * @extends DIntOrStringSet<string>
 */
class StringSet extends DIntOrStringSet implements Strings
{
    use Enforce\StringValuesTrait;

    public function join(string $separator): string
    {
        return implode($separator, $this->getValuesArray());
    }
}
