<?php

/**
 * This file is part of Tatter Tools.
 *
 * (c) 2021 Tatter Software
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tatter\Tools;

/**
 * Composer Toolkit
 * composer.php.
 *
 * Description: Applies standards to composer.json
 * Usage: php composer.php /path/to/composer.json
 */
$args ??= $argv ?? [];
if (empty($args[1])) {
    echo 'Usage: php composer.php /path/to/composer.json' . PHP_EOL;

    return;
}

if (! $file = realpath($args[1])) {
    echo 'Missing file: "' . $args[1] . '"' . PHP_EOL;
    echo 'Usage: php composer.php /path/to/composer.json' . PHP_EOL;

    return;
}
if (! is_file($file)) {
    echo 'Invalid file supplied: "' . $args[1] . '"' . PHP_EOL;
    echo 'Usage: php composer.php /path/to/composer.json' . PHP_EOL;

    return;
}

echo "Processing {$file}..." . PHP_EOL;

// Read file contents
if (! $raw = file_get_contents($file)) {
    echo 'Unable to read file.' . PHP_EOL;

    return;
}

// Decode to an array
if (! $input = json_decode($raw, true)) {
    echo json_last_error_msg() . PHP_EOL;

    return;
}

// Determine the type
$type = empty($input['type']) ? 'library' : $input['type'];

// Rebuild with some defaults for missing fields
$output = [
    'name'        => $input['name'] ?? 'organization/name',
    'type'        => $type,
    'description' => $input['description'] ?? '',
    'keywords'    => $input['keywords'] ?? ['codeigniter', 'codeigniter4'],
    'homepage'    => $input['homepage'] ?? '',
    'license'     => $input['license'] ?? '',
    'authors'     => $input['authors'] ?? [
        [
            'name'     => '',
            'email'    => '',
            'homepage' => '',
            'role'     => 'Developer',
        ],
    ],
    'config' => $input['config'] ?? [
        'optimize-autoloader' => true,
        'sort-packages'       => true,
        'allow-plugins'       => [
            'infection/extension-installer' => true,
            'phpstan/extension-installer'   => true,
        ],
    ],
    'require'      => $input['require'] ?? ['php' => '^7.4 || ^8.0'],
    'require-dev'  => $input['require-dev'] ?? [], // Additional requirements added by main script
    'autoload'     => $input['autoload'] ?? [], // Additional handling below
    'autoload-dev' => $input['autoload-dev'] ?? [
        'psr-4' => ['Tests\\Support\\' => 'tests/_support'],
    ],
    'minimum-stability' => 'dev',
    'prefer-stable'     => true,
    'scripts'           => $input['scripts'] ?? [], // Additional handling below
];

if (! isset($input['autoload']['exclude-from-classmap'])) {
    $output['autoload']['exclude-from-classmap'] = ['**/Database/Migrations/**'];
}
if ($type === 'library' && ! isset($input['autoload']['psr-4'])) {
    $output['autoload']['psr-4'] = ['Organization\\Name\\' => 'src'];
}

// Replace old PHP version formats
$output['require']['php'] = '^7.4 || ^8.0';

// Add anything else from the previous file
$keys = array_keys($input);
sort($keys);

foreach ($keys as $key) {
    if (! isset($output[$key])) {
        $output[$key] = $input[$key];
    }
}

// Make sure development scripts are set
$output['scripts']['deduplicate'] = 'phpcpd app/ src/';
$output['scripts']['analyze']     = ['phpstan analyze', 'psalm', 'rector process --dry-run'];
$output['scripts']['inspect']     = 'deptrac analyze --cache-file=build/deptrac.cache';
$output['scripts']['mutate']      = 'infection --threads=2 --skip-initial-tests --coverage=build/phpunit';
$output['scripts']['retool']      = 'retool'; // that's us!
$output['scripts']['style']       = 'php-cs-fixer fix --verbose --ansi --using-cache=no';
$output['scripts']['test']        = 'phpunit';
$output['scripts']['ci']          = [
    'Composer\\Config::disableProcessTimeout',
    '@deduplicate',
    '@analyze',
    '@composer normalize --dry-run',
    '@test',
    // '@mutate', Disabled until most libraries handle mutations properly
    '@inspect',
    '@style',
];

// Patches are only relevant to projects
if ($type === 'project') {
    $output['scripts']['patch'] = 'patch';
}

// Format the contents
$contents = json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
file_put_contents($file, $contents);

echo 'File updated successfully.' . PHP_EOL;

return;
