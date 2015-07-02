<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 7/2/15
 * Time: 9:54 PM
 */


$link = mysql_connect($config['mysql']['host'], $config['mysql']['user'], $config['mysql']['password']);
mysql_select_db($config['mysql']['database']);

/**
 * Simple wrapper for querying db
 * @param $query
 * @return array
 */
function query($query){
    $result = mysql_query($query);
    $arrayResult = [];
    while($line = mysql_fetch_array($result, MYSQL_ASSOC)){
        $arrayResult[] = $line;
    }
    return $arrayResult;
}

function pageQuery($query, $page)
{
    global $config;
    $query .= ' LIMIT ' . intval($config['items_per_page']) . ' OFFSET '. intval($page);
    $result = mysql_query($query);
    $arrayResult = [];
    while($line = mysql_fetch_array($result, MYSQL_ASSOC)){
        $arrayResult[] = $line;
    }
    return $arrayResult;
}