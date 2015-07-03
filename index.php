<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 7/2/15
 * Time: 8:40 PM
 *
 *
 * Entry point of application, the only one thing we do here is calling function from router.php which
 * will handle user request.
 */
error_reporting(0);

require_once(__DIR__.'/config.php');
require_once(__DIR__.'/database.php');
require_once(__DIR__.'/router.php');

handleRoute();
mysql_close($link);