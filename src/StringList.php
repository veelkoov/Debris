<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

use Veelkoov\Debris\Base\DList;

/**
 * @extends DList<string>
 */
class StringList extends DList
{
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
