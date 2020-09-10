<?php

/**
 * Composer Toolkit
 * composer.php
 *
 * Applies standards to composer.json
 */

$file = realpath($argv[1]);

echo "Processing {$file}..." . PHP_EOL;

// Read file contents
if (! $raw = file_get_contents($file))
{
	echo "Unable to read file." . PHP_EOL;
	exit 1;
}

// Decode to an array
if (! $input = json_decode($raw))
{
	echo json_last_error_msg();
	exit 1;
}

// Determine the type
$type = empty($input['type']) ? 'library' : $input['type'];

// Rebuild in order with some defaults for missing fields
$output = [
	'name'        => $input['name'] ?? 'organization/name',
	'type'        => $type,
	'description' => $input['description'] ?? '',
	'keywords'    => $input['keywords'] ?? ['codeigniter', 'codeigniter4'],
	'homepage'    => $input['description'] ?? '',
	'license'     => $input['license'] ?? '',
	'authors'     => $input['authors'] ?? [
		'name'     => '',
		'email'    => '',
		'homepage' => '',
		'role'     => 'Developer'
	],
	'require'     => $input['require'] ?? [
		'php' => '>=7.2',
	],
	'require-dev' => $input['require-dev'] ?? [], // Additional requirements added by main script
	'autoload'    => $input['autoload'] ?? [
		'psr-4' => ['Organization\\\\Name\\\\' => 'src'],
	],
	'autoload-dev'    => $input['autoload-dev'] ?? [
		'psr-4' => ['Tests\\\\Support\\\\' => 'tests/_support']
	],
	'repositories' => $input['repositories'] ?? [
		['type' => 'vcs', 'url' => 'https://github.com/codeigniter4/CodeIgniter4']
	],
	'minimum-stability' => 'dev',
	'prefer-stable'     => true,
	'scripts'           => $input['scripts'] ?? [ // Additional requirements handled below
		'post-update-cmd' => ['composer dump-autoload']
	],
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
$output['scripts']['style']   = 'phpcs --standard=./vendor/codeigniter4/codeigniter4-standard/CodeIgniter4 tests/ '
	. ($type === 'project' ? 'app/' : 'src/');
$output['scripts']['test']    = 'phpunit';
