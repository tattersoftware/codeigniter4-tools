<?php

// rector.php

declare(strict_types=1);

/**
 * This file is part of Tatter Tools.
 *
 * (c) 2021 Tatter Software
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Php74\Rector\Property\TypedPropertyRector;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/app'
    ]);

    $parameters->set(Option::BOOTSTRAP_FILES, [
        // autoload specific file
        realpath(getcwd()) . '/vendor/autoload.php',
        realpath(getcwd()) . '/vendor/codeigniter4/codeigniter4/system/Test/bootstrap.php',
    ]);

    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_74);

    // import php 7.4 set list for php 7.4 features
    $containerConfigurator->import(SetList::PHP_74);

    // get services
    $services = $containerConfigurator->services();

    // register single rule
    $services->set(TypedPropertyRector::class);
};
