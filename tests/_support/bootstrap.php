<?php

/**
 * This file is part of Tatter Tools.
 *
 * (c) 2021 Tatter Software
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

define('ENVIRONMENT', 'testing');

defined('HOMEPATH')    || define('HOMEPATH', realpath(rtrim(getcwd(), '\\/ ')) . DIRECTORY_SEPARATOR);
defined('ROOTPATH')    || define('ROOTPATH', HOMEPATH);
defined('SOURCEPATH')  || define('SOURCEPATH', realpath(HOMEPATH . 'src/') . DIRECTORY_SEPARATOR);
defined('TESTPATH')    || define('TESTPATH', realpath(HOMEPATH . 'tests/') . DIRECTORY_SEPARATOR);
defined('SUPPORTPATH') || define('SUPPORTPATH', realpath(TESTPATH . '_support/') . DIRECTORY_SEPARATOR);
