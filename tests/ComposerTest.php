<?php

use Tests\Support\ToolsTestCase;

class ComposerTest extends ToolsTestCase
{
	public function testEmpty()
	{
		file_put_contents($this->composer, '');

		$expected = 'Processing ' . $this->composer . '...' . PHP_EOL . 'Unable to read file.' . PHP_EOL;
		$result   = $this->runComposer();

		$this->assertEquals($expected, $result);
	}

	public function testInvalid()
	{
		$this->stageComposer('invalid.json');

		$expected = 'Processing ' . $this->composer . '...' . PHP_EOL . 'Syntax error' . PHP_EOL;
		$result   = $this->runComposer();

		$this->assertEquals($expected, $result);
	}

	public function testValidOutput()
	{
		$this->stageComposer();

		$expected = 'Processing ' . $this->composer . '...' . PHP_EOL . 'File updated successfully.' . PHP_EOL;
		$result   = $this->runComposer();

		$this->assertEquals($expected, $result);
	}

	public function testValidManipulatesFile()
	{
		$this->stageComposer();
		$this->runComposer();

		$result = $this->getComposer();

		// Verify existing value
		$this->assertEquals('MIT', $result['license']);

		// Verify new field
		$this->assertEquals('phpstan analyze', $result['scripts']['analyze']);
	}
}
