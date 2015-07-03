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

function pageQuery($page, $params =[])
{
    global $config;
    $query = 'SELECT * FROM products ';
    if(intval($page) !== 1){
        if(array_key_exists('last', $params)){
            $query .= 'WHERE id > '.$params['last'];
        }
    }
    if(empty($params))
        $query .= ' ORDER BY id desc';
    else{
        if(array_key_exists('order', $params)){
            if($params['sort'] === 'price')
                $query .= ' FORCE INDEX(price_id_idx)';
            $query .= ' ORDER BY '.mysql_real_escape_string($params['sort']);
        }
        if(array_key_exists('sort', $params)){
            if(strtolower($params['order']) === 'desc')
                $query .= ' desc';
        }
    }
    if(intval($page) === 1)
        $query .= ' LIMIT ' . intval($config['items_per_page']);
    else{
        if(!array_key_exists('last', $params)){
            $query .= ' LIMIT ' . intval($config['items_per_page']).' OFFSET '.(intval($page) - 1)*intval($config['items_per_page']);
        }
    }
    $result = mysql_query($query);
    $arrayResult = [];
    while($line = mysql_fetch_array($result, MYSQL_ASSOC)){
        $arrayResult[] = $line;
    }
//    var_dump($arrayResult); die();
    return $arrayResult;
}


function deleteProduct($id)
{
    $id = intval($id);
    mysql_query('DELETE from products WHERE id='.$id);
}
