<?php

namespace Veelkoov\Debris\Tests\Utils;

final readonly class Sortable implements \Stringable, \JsonSerializable
{
    public function __construct(
        private string $value,
    ) {}

    public function __toString(): string
    {
        return $this->value;
    }

    public function jsonSerialize(): mixed
    {
        return $this->value;
    }
}
