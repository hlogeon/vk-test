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

function handleRoute()
{
    global $routes;
    foreach($routes as $key => $handler){
        if($_SERVER['REQUEST_URI'] === $key){
            if(is_string($handler) && is_callable($handler)){
                $handler();
            } elseif(is_callable($handler)){
                call_user_func($handler);
            }
            else{

            }
        }
    }
}

function home()
{
    echo "Hello!";
}