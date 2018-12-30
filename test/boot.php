<?php
/**
 * phpunit
 */

error_reporting(E_ALL);
ini_set('display_errors', 'On');
date_default_timezone_set('Asia/Shanghai');

if (is_file(dirname(__DIR__, 3) . '/autoload.php')) {
    require dirname(__DIR__, 3) . '/autoload.php';
}
