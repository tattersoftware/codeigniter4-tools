<?php

use Nexus\CsConfig\Factory;
use PhpCsFixer\Finder;
use Tatter\Tools\Standard;

$finder = Finder::create()
    ->files()
    ->in(__DIR__)
    ->exclude('build')
    ->append([__FILE__]);

// Remove overrides for incremental changes
$overrides = [
	'array_indentation' => false,
	'braces'            => false,
	'indentation_type'  => false,
];

$options = [
    'finder'    => $finder,
    'cacheFile' => 'build/.php-cs-fixer.cache',
];

return Factory::create(new Standard(), $overrides, $options)->forProjects();
