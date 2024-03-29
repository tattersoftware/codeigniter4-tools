<?php

declare(strict_types=1);

use ComposerUnused\ComposerUnused\Configuration\Configuration;
use ComposerUnused\ComposerUnused\Configuration\NamedFilter;
use ComposerUnused\ComposerUnused\Configuration\PatternFilter;
use Webmozart\Glob\Glob;

return static function (Configuration $config): Configuration {
    return $config
        ->addNamedFilter(NamedFilter::fromString('codeigniter4/devkit'))
        ->setAdditionalFilesFor('codeigniter4/framework', [
            ...Glob::glob(__DIR__ . '/vendor/codeigniter4/framework/system/Helpers/*.php'),
        ]);
};
