# Tatter\Tools
Developer tools for CodeIgniter 4

[![](https://github.com/tattersoftware/codeigniter4-tools/workflows/PHPUnit/badge.svg)](https://github.com/tattersoftware/codeigniter4-tools/actions/workflows/test.yml)
[![](https://github.com/tattersoftware/codeigniter4-tools/workflows/PHPStan/badge.svg)](https://github.com/tattersoftware/codeigniter4-tools/actions/workflows/analyze.yml)
[![](https://github.com/tattersoftware/codeigniter4-tools/workflows/Deptrac/badge.svg)](https://github.com/tattersoftware/codeigniter4-tools/actions/workflows/inspect.yml)
[![Coverage Status](https://coveralls.io/repos/github/tattersoftware/codeigniter4-tools/badge.svg?branch=develop)](https://coveralls.io/github/tattersoftware/codeigniter4-tools?branch=develop)

## Installation

* Install via Composer: `> composer require tatter/tools`

## Included

### Support Tools

* [Tatter/Patches](https://github.com/tattersoftware/codeigniter4-patches)

### Styles and Standards

* [CodeIgniter Coding Standard](https://github.com/CodeIgniter/coding-standard)
* Ergebnis' Composer Normalize
* NexusPHP CS Config
* PHP CS Fixer

### Testing and Analysis

* NexusPHP Tachycardia
* PHP Coveralls
* PHPStan
* PHPUnit

### Mocking

* FakerPHP
* VFS Stream

## Usage

### Applying Toolkit

Apply the development toolkit using the bash script:

* ./vendor/tatter/tools/src/apply.sh <path_to_project_repository>

The script will create

### Spark Autocomplete

**Tools** includes a directive for Bash's
[Programmable Completion](http://www.gnu.org/software/bash/manual/bash.html#Programmable-Completion)
to allow tab-completing `spark` commands from the command-line.
Simply copy **src/spark_completion** to you Bash completions directory as "spark" and
re-source your environment (i.e. log out & in):

	sudo cp src/spark_completion /usr/share/bash-completion/completions/spark
	exit

Now when accessing commands for in CodeIgniter 4 you can autocomplete against
the list of available commands for your instance:

	> ./spark mi
	[tab]
	> ./spark migrate
	migrate           migrate:create    migrate:refresh   migrate:rollback  migrate:status
