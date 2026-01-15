<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Lists;

use Veelkoov\Debris\Base\Collection;
use Veelkoov\Debris\Base\DList;
use Veelkoov\Debris\Base\Enforce;
use Veelkoov\Debris\Collections\Strings;
use Veelkoov\Debris\Sets\StringSet;

/**
 * @implements Collection<int, string>
 *
 * @extends DList<string>
 */
class StringList extends DList implements Collection, Strings
{
    use Enforce\StringValuesTrait;

    public function join(string $separator): string
    {
        return implode($separator, $this->items);
    }

    /**
     * @param non-empty-string $separator
     */
    public static function split(string $separator, string $input): static
    {
        return new static('' === $input ? [] : explode($separator, $input));
    }

    public function toSet(): StringSet
    {
        return new StringSet($this->items);
    }
}
