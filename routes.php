<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 7/2/15
 * Time: 8:53 PM
 */

$routes = [
    '/view/{id}' => 'view_record',
    '/' => 'home',
    '/list/{page}' => 'product_list',
];


function view_record($id)
{
    if(!is_numeric($id))
        renderNotFound();
    $product = query('SELECT * FROM products WHERE id='.intval($id));
    if(!$product)
        renderNotFound();
    $product = array_shift($product);
    var_dump($product);
}


function home()
{
    $title = "Hello!";
    $products = query('SELECT * FROM products LIMIT 4');
    include(__DIR__.'/views/home.php');
}

function product_list($page)
{
    if(!is_numeric($page))
        renderNotFound();
    $products = pageQuery('SELECT * FROM products ORDER BY id desc', $page);
    $next = '/list/' . (intval($page) + 1);
    if(intval($page) > 1)
        $previous = '/list/' . (intval($page) - 1);
    else
        $previous = false;
    include(__DIR__.'/views/list.php');
}
