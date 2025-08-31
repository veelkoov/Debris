<?php

declare(strict_types=1);

namespace Veelkoov\Debris\Base\Internal;

use Veelkoov\Debris\Exception\ChangingImmutableException;

/**
 * @internal
 */
final class Freezer
{
    public function __construct(
        private readonly object $protected,
        private bool $frozen = true,
    ) {}

    public function protect(): void
    {
        if ($this->frozen) {
            throw new ChangingImmutableException($this->protected::class);
        }
    }

    public function freeze(): void
    {
        $this->frozen = true;
    }
}
