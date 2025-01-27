<?php

declare(strict_types=1);

namespace Veelkoov\Debris;

class ChangingImmutableException extends \RuntimeException
{
    public function __construct(string $sourceClass)
    {
        parent::__construct("Tried to modify immutable {$sourceClass}");
    }
}
