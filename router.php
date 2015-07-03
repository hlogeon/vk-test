<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 7/2/15
 * Time: 8:40 PM
 *
 * Simple procedural routing
 */
require_once(__DIR__.'/routes.php');

const PARAMETRIZED_URI_PARTS_COUNT = 3;

/**
 * Get request URIm compare to existing routes
 * Calls route dispatcher and binds parameter to it if it was found
 */
function handleRoute()
{
    global $routes;
    if($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET')
        methodNotAllowed();
    foreach($routes as $route => $dispatcher){
        $match = routeMatchParts($route, $_SERVER['REQUEST_URI']);
        if($match === true){
            callRoute($dispatcher);
            return;
        }
        elseif($match !== false){
            callRouteWithParam($dispatcher, $match);
            return;
        }
    }
    renderNotFound();
}

/**
 * Simply compares if route has same amount of parts splited by "/"
 * and the first part is the same
 *
 * @param string $route
 * @param string $uri
 * @return bool
 */
function routeMatchParts($route, $uri)
{
    $routeParts = explode('/', $route);
    $uriParts = explode('/', $uri);
    if(count($routeParts) !== count($uriParts))
        return false;
    if($routeParts[1] !== $uriParts[1])
        return false;
    if(count($uriParts) === PARAMETRIZED_URI_PARTS_COUNT)
        return $uriParts[2];

    return true;
}

/**
 * Call route dispatcher and binds param to it
 * @param $dispatcher
 * @param $param
 */
function callRouteWithParam($dispatcher, $param)
{
    if(is_string($dispatcher) && is_callable($dispatcher)){
        $dispatcher($param, parseQuery());
    } elseif(is_callable($dispatcher)){
        call_user_func($dispatcher, $param, parseQuery());
    }
}


/**
 * Simply call dispatcher
 * @param $dispatcher
 */
function callRoute($dispatcher)
{

    if(is_string($dispatcher) && is_callable($dispatcher)){
        $dispatcher(parseQuery());
    } elseif(is_callable($dispatcher)){
        call_user_func($dispatcher, parseQuery());
    }
}

function parseQuery()
{
    $q = $_SERVER['QUERY_STRING'];
    if(strlen($q) === 0)
        return [];
    $queryParts = explode('&', $q);
    $params = [];
    foreach($queryParts as $part){
        $partDetails = explode('=', $part);
        if(count($partDetails) === 2)
            $params[$partDetails[0]] = $partDetails[1];
        else
            $params[$partDetails[0]] = null;
    }
    return $params;
}


function renderNotFound()
{

}


function renderAjaxNotFound()
{

}


function methodNotAllowed()
{

}

function renderError($message)
{
    include(__DIR__.'/views/error.php');
    die();
}