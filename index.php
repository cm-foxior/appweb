<?php
/**
 * @package    Valkyrie.Platform
 *
 * @copyright  Copyright (C) CodeMonkey - Platform. All Rights Reserved.
 * @license    You can see LICENSE.txt
 * @version    1.0
 * Build date  27 Jul 15
 *
 * @author     CodeMonkey <info@codemonkey.com.mx>
 * Website     https://www.codemonkey.com.mx/
 */

define('_EXEC', 1);

if(version_compare(PHP_VERSION, '5.4', '<'))
    die('Your host needs to use PHP 5.4 or higher to run this version of Valkyrie Platform.');

if (!defined('_DEFINES'))
{
    define('PATH_ROOT', __DIR__);
    require_once PATH_ROOT . '/includes/defines.php';
}

spl_autoload_register(function($class)
{
    if (file_exists(PATH_LIBRARIES . $class . CLASS_PHP))
	    require PATH_LIBRARIES . $class . CLASS_PHP;

    if (file_exists(PATH_MY_LIBRARIES . $class . CLASS_PHP))
	    require PATH_MY_LIBRARIES . $class . CLASS_PHP;
});

$layout = new Layout();
echo $layout->execute();
