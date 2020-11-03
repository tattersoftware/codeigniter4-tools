<?php namespace Tatter\Tools;

/**
 * Composer Toolkit
 * composer.php
 *
 * Description: Applies standards to composer.json
 * Usage: php composer.php /path/to/composer.json
 */

$args = $args ?? $argv ?? [];
if (empty($args[1]))
{
	echo 'Usage: php composer.php /path/to/composer.json' . PHP_EOL;
	return;
}

if (! $file = realpath($args[1]))
{
	echo 'Missing file: "' . $args[1] . '"' . PHP_EOL;
	echo 'Usage: php composer.php /path/to/composer.json' . PHP_EOL;
	return;
}
if (! is_file($file))
{
	echo 'Invalid file supplied: "' . $args[1] . '"' . PHP_EOL;
	echo 'Usage: php composer.php /path/to/composer.json' . PHP_EOL;
	return;
}

echo "Processing {$file}..." . PHP_EOL;

// Read file contents
if (! $raw = file_get_contents($file))
{
	echo 'Unable to read file.' . PHP_EOL;
	return;
}

// Decode to an array
if (! $input = json_decode($raw, true))
{
	echo json_last_error_msg() . PHP_EOL;;
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
		]
	],
	'require'     => $input['require'] ?? [
		'php' => '>=7.2',
	],
	'require-dev' => $input['require-dev'] ?? [], // Additional requirements added by main script
	'autoload'    => $input['autoload'] ?? [
		'psr-4' => ['Organization\\Name\\' => 'src'],
	],
	'autoload-dev'    => $input['autoload-dev'] ?? [
		'psr-4' => ['Tests\\Support\\' => 'tests/_support']
	],
	'minimum-stability' => 'dev',
	'prefer-stable'     => true,
	'scripts'           => $input['scripts'] ?? [], // Additional requirements handled below
];

// Add anything else from the previous file
$keys = array_keys($input);
sort($keys);
foreach ($keys as $key)
{
	if (! isset($output[$key]))
	{
		$output[$key] = $input[$key];
	}
}

// Make sure development scripts are set
$output['scripts']['analyze'] = 'phpstan analyze';
$output['scripts']['style']   = 'phpcbf --standard=./vendor/codeigniter4/codeigniter4-standard/CodeIgniter4 tests/ ' . ($type === 'project' ? 'app/' : 'src/');
$output['scripts']['test']    = 'phpunit';

// Format the contents
$contents = json_encode($output, JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) . PHP_EOL;
$contents = str_replace('    ', "\t", $contents);
file_put_contents($file, $contents);

echo 'File updated successfully.' . PHP_EOL;

return;
