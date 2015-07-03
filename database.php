<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 7/2/15
 * Time: 9:54 PM
 */

$memcache = new Memcached();
$memcache->addServer($config['memcache']['host'], $config['memcache']['port']);

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

/**
 * Query with paging
 *
 * @param $page
 * @param array $params
 * @return array
 */
function pageQuery($page, $params =[])
{
    global $memcache;
    $key = $page.implode(':', $params);
    if($result = $memcache->get($key)){
        return $result;
    }
    $query = 'SELECT * FROM products ';
    buildOrderQuery($params, $query);
    buildLimitQuery($page, $params, $query);
    $result = mysql_query($query);
    $arrayResult = [];
    while($line = mysql_fetch_array($result, MYSQL_ASSOC)){
        $arrayResult[] = $line;
    }
    $memcache->set($key, $arrayResult, 15);
    return $arrayResult;
}


/**
 * Add limit conditions to existing query
 *
 * @param $page
 * @param $params
 * @param $query
 */
function buildLimitQuery($page, $params, &$query)
{
    global $config;
    if(intval($page) === 1)
        $query .= ' LIMIT ' . intval($config['items_per_page']);
    else{
        if(!array_key_exists('last', $params)){
            $query .= ' LIMIT ' . intval($config['items_per_page']).' OFFSET '.(intval($page) - 1)*intval($config['items_per_page']);
        } else
            $query .= ' LIMIT ' . intval($config['items_per_page']);
    }
}

/**
 * Add order conditions to existing query
 *
 * @param $params
 * @param $query
 */
function buildOrderQuery($params, &$query)
{
    if(empty($params)){
        $query .= defaultOrder();
        return;
    }
    $forceIndex = false;
    if(array_key_exists('sort', $params)) {
        if ($params['sort'] === 'price'){
            $query .= ' FORCE INDEX(price_id_idx)';
            $forceIndex = true;
        }
    }
    if(!array_key_exists('lastPrice', $params) && !array_key_exists('firstPrice', $params)){
        numProductProvided($params, $query, 'first', '>');
        numProductProvided($params, $query, 'last', '<');
    } else{
        priceProductProvided($params, $query, 'first', '>');
        priceProductProvided($params, $query, 'last', '<');
    }
    sortConditionProvided($params, $query);
    orderConditionProvided($params, $query, $forceIndex);
}

/**
 * Add WHERE condition to speed-up query if first and/or last id on page were provided
 *
 * @param $params
 * @param $query
 * @param $key
 * @param $defaultOperator
 */
function numProductProvided($params, &$query, $key, $defaultOperator)
{
    $inverseOperator = $defaultOperator === '>' ? '<' : '>';
    if(array_key_exists($key, $params)){
        if(array_key_exists('order', $params) && strtolower($params['order']) === 'desc')
            $query .= ' WHERE id ' . $defaultOperator. ' ' . intval($params[$key]);
        elseif(array_key_exists('order', $params) && strtolower($params['order']) === 'asc')
            $query .= ' WHERE id ' . $inverseOperator . ' ' . intval($params[$key]);
        else
            $query .= ' WHERE id ' . $defaultOperator . ' ' . intval($params[$key]).defaultOrder();
    }
}


/**
 *
 * Add WHERE condition to speed-up query if first and/or last prices on page were provided
 *
 * @param $params
 * @param $query
 * @param $key
 * @param $defaultOperator
 */
function priceProductProvided($params, &$query, $key, $defaultOperator)
{
    $inverseOperator = $defaultOperator === '>' ? '<' : '>';
    if(array_key_exists($key, $params)){
        if(array_key_exists('order', $params) && strtolower($params['order']) === 'desc')
            $query .= ' WHERE id ' . $defaultOperator. ' ' . intval($params[$key]) . ' AND price '. $defaultOperator.'=' .' '. intval($params[$key.'Price']);
        elseif(array_key_exists('order', $params) && strtolower($params['order']) === 'asc')
            $query .= ' WHERE id ' . $inverseOperator . ' ' . intval($params[$key]). ' AND price '. $inverseOperator.'=' .' '. intval($params[$key.'Price']);
        else
            $query .= ' WHERE id ' . $defaultOperator . ' ' . intval($params[$key]) .
                ' AND price '. $defaultOperator .'=' .' '. intval($params['price'.ucfirst($key)])
                .defaultOrder();
    }
}


/**
 * Add sort condition if necessary
 * @param $params
 * @param $query
 */
function sortConditionProvided($params, &$query)
{
    if(array_key_exists('sort', $params)){
        $query .= ' ORDER BY '.mysql_real_escape_string($params['sort']);
    }
}


/**
 * Add order condition if necessary
 * @param $params
 * @param $query
 * @param bool $forceIndex
 */
function orderConditionProvided($params, &$query, $forceIndex = false)
{
    if(array_key_exists('order', $params)){
        if(strtolower($params['order']) === 'desc')
            $query .= ' desc';
    } elseif($forceIndex === true){
        $query .= ' asc';
    }
}

/**
 * Define default order
 * @return string
 */
function defaultOrder()
{
    return ' ORDER BY id DESC';
}
