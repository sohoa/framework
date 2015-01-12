<?php

use Symfony\CS\FixerInterface;

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->notName('.gitignore')
    ->notName('.php_cs')
    ->notName('composer.*')
    ->notName('*.tpl.php')
    ->notName('*.yml')
    ->notName('*.md')
    ->notName('*.phar')
    ->exclude('vendor')
    ->exclude('Tests')
    ->in(__DIR__)
;

return Symfony\CS\Config\Config::create()
// ->fixers(array('indentation',
//                'trailing_spaces',
//                '-linefeed',
//                'unused_use',
//                'phpdoc_params',
//                'short_tag',
//                'return',
//                'visibility',
//                'php_closing_tag',
//                'braces',
//                'extra_empty_lines',
//                'function_declaration',
//                'include',
//                'controls_spaces',
//                'psr0',
//                'elseif',
//                'eof_ending'))
    ->finder($finder)
;