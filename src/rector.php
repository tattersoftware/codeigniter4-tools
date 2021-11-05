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
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {

    // Import set lists up to and including PHP 7.4 features
    $containerConfigurator->import(LevelSetList::UP_TO_PHP_74);
    $containerConfigurator->import(SetList::PHP_74);

    // Get parameters
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/app',
    ]);

    $parameters->set(Option::BOOTSTRAP_FILES, [
        // autoload specific file
        realpath(getcwd()) . '/vendor/autoload.php',
        realpath(getcwd()) . '/vendor/codeigniter4/codeigniter4/system/Test/bootstrap.php',
    ]);

    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_74);

    // Some opinionated rules that may merit skipping
    $parameters->set(Option::SKIP, [
        \Rector\Php71\Rector\FuncCall\CountOnNullRector::class,
        \Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector::class,
    ]);

    // get services
    $services = $containerConfigurator->services();

    // register single rule
    $services->set(TypedPropertyRector::class);
};
