<?php

namespace Veelkoov\Debris\Tests\DebrisTestsUtils;

/**
 * @internal
 */
final readonly class Sortable implements \Stringable, \JsonSerializable
{
    public function __construct(
        private string $value,
    ) {}

    #[\Override]
    public function __toString(): string
    {
        return $this->value;
    }

    #[\Override]
    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}
