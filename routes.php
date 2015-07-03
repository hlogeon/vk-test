<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 7/2/15
 * Time: 8:53 PM
 *
 *
 *
 *
 * THIS FILE DESCRIBES ALL ROUTES AVAILABLE IN APPLICATION. It may also contain handlers
 */
require_once(__DIR__.'/model/product.php');

/**
 *
 * Define your application routes here!
 * $key is the route pattern and $value is it's dispatcher
 * You can specify dispatcher as a string, what will mean that you provided callable function name
 * or anonymous function. Note that for some reason, each function should accept at least 1 argument
 * @var array
 */
$routes = [
    '/view/{id}' => 'view_record',
    '/delete/{id}' => function($id){
        product_delete($id);
        header('Location: '.$_SERVER['HTTP_REFERER']);
    },
    '/' => 'home',
    '/list/{page}' => function($page, $arguments = []){
        $view = __DIR__.'/views/list.php';
        product_list($page, $view, $arguments);
    },
    '/ajax-list/{page}' => function($page, $arguments){
        $view = __DIR__.'/views/items.php';
        product_list($page, $view, $arguments);
    },
    '/add' => 'add_product',
    '/edit/{id}' => function($id, $arguments = []){
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $product = product_find_by_pk($id);
            $form_action = '/edit/'.$id;
            include(__DIR__.'/views/layout/header.php');
            include(__DIR__.'/views/add_product_form.php');
            include(__DIR__.'/views/layout/footer.php');
        } else{
            product_update($_POST);
            header('Location: '.$_SERVER['HTTP_REFERER']);
        }

    },
];

/**
 * Show product by id provided
 *
 * @param $id
 * @param array $arguments
 */
function view_record($id, $arguments = [])
{
    global $memcache;
    if(!is_numeric($id))
        renderNotFound();
    $product = product_find_by_pk($id);
    include(__DIR__.'/views/item.php');
}


/**
 * Renders home page
 * @param array $arguments
 */
function home($arguments = [])
{
    $title = "Hello!";
    $products = query('SELECT * FROM products LIMIT 4');
    include(__DIR__.'/views/home.php');
}


/**
 * Find all products that should be rendered on the page provided in arguments
 * Create pagination variables
 * Render view provided in arguments
 * This action currently used in /list route and ajax-list route
 *
 * @param integer $page
 * @param string $view
 * @param array $arguments
 */
function product_list($page, $view, $arguments = [])
{
    if(!is_numeric($page))
        renderNotFound();
    $products = pageQuery($page, $arguments);
    $last = end($products);
    $first = $products[0];
    $next = product_make_pager_link($page, $arguments, $last);
    if(intval($page) > 1)
        $previous = product_make_pager_link($page, $arguments, $first, LINK_PREV);
    else
        $previous = false;
    include($view);
}


/**
 * Handle add product request
 */
function add_product()
{
    if($_SERVER['REQUEST_METHOD'] === 'GET')
        renderError('Нельзя так, только POST');

        product_add($_POST);
        header('Location: '.$_SERVER['HTTP_REFERER']);
}


/**
 * Retrieve current query string and remove "first" and "next" params from
 * it.
 * @return string
 */
function makePagerQueryString()
{
    $parts = explode('&', $_SERVER['QUERY_STRING']);
    $result = '';
    foreach($parts as $pair){
        $pairParts = explode('=', $pair);
        if($pairParts[0] !== 'last' && $pairParts[0] !== 'first')
            $result .= strlen($result) === 0 ? implode('=', $pairParts) : '&'.implode('=', $pairParts);
    }
    return $result;
}