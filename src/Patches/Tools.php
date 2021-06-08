<?php

namespace Tatter\Tools\Patches;

use Tatter\Patches\BaseSource;
use Tatter\Patches\Interfaces\SourceInterface;

class Tools extends BaseSource implements SourceInterface
{
	/**
	 * Array of paths to check during patching.
	 * Required: from (absolute), to (relative)
	 * Optional: exclude.
	 *
	 * @var array
	 */
	public $paths = [
		[
			'from' => ROOTPATH . 'vendor/tatter/tools/src/Common',
			'to'   => '',
		],
		[
			'from' => ROOTPATH . 'vendor/tatter/tools/src/Project',
			'to'   => '',
		],
	];
}
