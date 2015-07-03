<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 7/2/15
 * Time: 8:40 PM
 */
require_once(__DIR__.'/config.php');
require_once(__DIR__.'/database.php');
require_once(__DIR__.'/router.php');
handleRoute();

mysql_close($link);