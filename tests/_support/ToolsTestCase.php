<?php namespace Tests\Support;

use PHPUnit\Framework\TestCase;

class ToolsTestCase extends TestCase
{
	/**
	 * @var string
	 */
	protected $composer;

	protected function setUp(): void
	{
		parent::setUp();
		$this->composer = tempnam(sys_get_temp_dir(), 'composer-');
	}

	/**
	 * Copies the a support composer.json file
	 * to the temp file.
	 *
	 * @param string $basename Relative file name in _support/Composer/
	 */
	protected function stageComposer(string $basename = 'composer.json')
	{
		$file = SUPPORTPATH . 'Composer' . DIRECTORY_SEPARATOR . $basename;

		file_put_contents($this->composer, file_get_contents($file));
	}

	/**
	 * Includes the composer.php file and returns
	 * the command output.
	 *
	 * @return string
	 */
	protected function runComposer(): string
	{
		$args = [
			SOURCEPATH . 'composer.php',
			$this->composer,
		];

        ob_start();
        include $args[0];
        return ob_get_clean();
	}

	/**
	 * Gets the contents of the temporary composer
	 * file as a decoded array.
	 *
	 * @return array
	 * @throws \RuntimeException
	 */
	protected function getComposer(): array
	{
        return json_decode(file_get_contents($this->composer), true);
	}
}
