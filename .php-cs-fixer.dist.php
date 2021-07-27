<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([])
    ->setFinder($finder)
    ->setCacheFile('.php-cs-fixer.cache')
;
