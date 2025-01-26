<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

/**
 * @extends DList<string>
 */
class StringList extends DList
{
    public function join(string $separator): string
    {
        return implode($separator, $this->items);
    }

    public function toSet(): StringSet
    {
        return new StringSet($this->items);
    }
}
