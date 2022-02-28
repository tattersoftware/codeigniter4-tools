# Tatter\Tools
Developer tools for CodeIgniter 4

[![](https://github.com/tattersoftware/codeigniter4-tools/workflows/PHPUnit/badge.svg)](https://github.com/tattersoftware/codeigniter4-tools/actions/workflows/test.yml)
[![](https://github.com/tattersoftware/codeigniter4-tools/workflows/PHPStan/badge.svg)](https://github.com/tattersoftware/codeigniter4-tools/actions/workflows/analyze.yml)
[![](https://github.com/tattersoftware/codeigniter4-tools/workflows/Deptrac/badge.svg)](https://github.com/tattersoftware/codeigniter4-tools/actions/workflows/inspect.yml)
[![Coverage Status](https://coveralls.io/repos/github/tattersoftware/codeigniter4-tools/badge.svg?branch=develop)](https://coveralls.io/github/tattersoftware/codeigniter4-tools?branch=develop)

## Installation

* Install via Composer: `> composer require tatter/tools`

## Description

**Tools** is an opinionated wrapper for the [CodeIgniter DevKit](https://github.com/codeigniter4/devkit/).
It includes a script to apply the DevKit to libraries (instead of the default, for projects)
and some custom updates to the template files.

## Included

See the [DevKit docs](https://github.com/codeigniter4/devkit/blob/develop/README.md) for a
complete list of bundled tools.

## Usage

### Applying Toolkit

Apply the development toolkit using the bash script from the directory where you wish it to apply:

* ./vendor/tatter/tools/src/retool
... or:
* composer retool

## Spark Autocomplete

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
