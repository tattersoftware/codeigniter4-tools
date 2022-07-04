<?php

/**
 * This file is part of Tatter Tools.
 *
 * (c) 2021 Tatter Software
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

use Tests\Support\ToolsTestCase;

/**
 * @internal
 */
final class ComposerTest extends ToolsTestCase
{
    private string $composer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->composer = tempnam(sys_get_temp_dir(), 'composer-');
    }

    //--------------------------------------------------------------------

    public function testHelp()
    {
        $this->composer = '';

        $expected = 'Usage: php composer.php /path/to/composer.json' . PHP_EOL;
        $result   = $this->runComposer();

        $this->assertSame($expected, $result);
    }

    public function testMissingFile()
    {
        $this->composer = 'banana';

        $expected = 'Missing file: "banana"';
        $result   = $this->runComposer();

        $this->assertStringContainsString($expected, $result);
    }

    public function testDirectory()
    {
        $this->composer = SUPPORTPATH;

        $expected = 'Invalid file supplied: "' . SUPPORTPATH . '"';
        $result   = $this->runComposer();

        $this->assertStringContainsString($expected, $result);
    }

    public function testEmptyFile()
    {
        file_put_contents($this->composer, '');

        $expected = 'Processing ' . $this->composer . '...' . PHP_EOL . 'Unable to read file.' . PHP_EOL;
        $result   = $this->runComposer();

        $this->assertSame($expected, $result);
    }

    public function testInvalidFile()
    {
        $this->stageComposer('invalid.json');

        $expected = 'Processing ' . $this->composer . '...' . PHP_EOL . 'Syntax error' . PHP_EOL;
        $result   = $this->runComposer();

        $this->assertSame($expected, $result);
    }

    public function testValidOutput()
    {
        $this->stageComposer();

        $expected = 'Processing ' . $this->composer . '...' . PHP_EOL . 'File updated successfully.' . PHP_EOL;
        $result   = $this->runComposer();

        $this->assertSame($expected, $result);
    }

    public function testValidManipulatesFile()
    {
        $this->stageComposer();
        $this->runComposer();

        $result = $this->getComposer();

        // Verify existing value
        $this->assertSame('MIT', $result['license']);

        // Verify new field
        $this->assertSame(['phpstan analyze', 'psalm', 'rector process --dry-run'], $result['scripts']['analyze']);
    }

    /**
     * Copies the a support composer.json file
     * to the temp file.
     *
     * @param string $basename Relative file name in _support/Composer/
     */
    private function stageComposer(string $basename = 'composer.json')
    {
        $file = SUPPORTPATH . 'Composer' . DIRECTORY_SEPARATOR . $basename;

        file_put_contents($this->composer, file_get_contents($file));
    }

    /**
     * Includes the composer.php file and returns
     * the command output.
     */
    private function runComposer(): string
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
     */
    private function getComposer(): array
    {
        return json_decode(file_get_contents($this->composer), true);
    }
}
