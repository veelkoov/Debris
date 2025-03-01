<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PhpCsFixer' => true,
        '@PhpCsFixer:risky' => true,
        'php_unit_method_casing' => false, // These are supposed to be descriptive, not pretty.
    ])
    ->setFinder($finder)
;
