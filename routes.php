<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 7/2/15
 * Time: 8:53 PM
 */
require_once(__DIR__.'/model/product.php');

$routes = [
    '/view/{id}' => 'view_record',
    '/delete/{id}' => function($id){
        deleteProduct($id);
        header('Location: '.$_SERVER['HTTP_REFERER']);
    },
    '/' => 'home',
    '/list/{page}' => 'product_list',
    '/ajax-list/{page}' => 'ajax_product_list',
    '/add' => 'add_product',
];


function view_record($id, $arguments = [])
{
    if(!is_numeric($id))
        renderNotFound();
    $product = query('SELECT * FROM products WHERE id='.intval($id));
    if(!$product)
        renderNotFound();
    $product = array_shift($product);
    var_dump($product);
}


function home($arguments = [])
{
    $title = "Hello!";
    $products = query('SELECT * FROM products LIMIT 4');
    include(__DIR__.'/views/home.php');
}

function product_list($page, $arguments = [])
{
    if(!is_numeric($page))
        renderNotFound();
    $products = pageQuery($page, $arguments);
    $next = '/ajax-list/' . (intval($page) + 1).'?'.$_SERVER['QUERY_STRING'];
    if(intval($page) > 1)
        $previous = '/ajax-list/' . (intval($page) - 1).'?'.$_SERVER['QUERY_STRING'];
    else
        $previous = false;
    include(__DIR__.'/views/list.php');
}


function ajax_product_list($page, $arguments = [])
{
    if(!is_numeric($page))
        renderAjaxNotFound();
    $products = pageQuery($page, $arguments);
    $next = '/ajax-list/' . (intval($page) + 1).'?'.$_SERVER['QUERY_STRING'];
    if(intval($page) > 1)
        $previous = '/ajax-list/' . (intval($page) - 1).'?'.$_SERVER['QUERY_STRING'];
    else
        $previous = false;
    include(__DIR__.'/views/items.php');
}


function add_product()
{
    if($_SERVER['REQUEST_METHOD'] === 'GET')
        renderError('Нельзя так, только POST');
    if(!array_key_exists('title', $_POST))
        renderError('Упс, кажется, ты забыл указать название');
    elseif(!array_key_exists('description', $_POST))
        renderError('Ой-ой, кажется, ты забыл написать описание');
    elseif(!array_key_exists('price', $_POST))
        renderError('Стой, кажется, ты забыл указать цену');
    elseif(!array_key_exists('image', $_POST))
        renderError('Арррр, кажется, ты забыл указать цену');
    else{
        product_add($_POST);
        header('Location: '.$_SERVER['HTTP_REFERER']);
    }
}