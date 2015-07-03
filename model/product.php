<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 7/3/15
 * Time: 5:49 AM
 *
 *
 *
 * THIS FILE CONTAINS SPECIFIC FUNCTION FOR PRODUCTS TABLE AND SOME HELPER FUNCTIONS
 *
 */


const LINK_NEXT = 0;
const LINK_PREV = 1;


/**
 * Return array of available attributes where key is the name and value is the value
 * @return array
 */
function product_attributes()
{
    return [
        'title' => 'string',
        'price' => 'numeric',
        'image' => 'string',
        'description' => 'string',
    ];
}


/**
 * Validate product and add it to db if validation passed OK
 *
 * @param array $input
 */
function product_add(array $input)
{
    product_validate_input($input);
    $query = 'INSERT INTO products';
    $values = '';
    $i = 0;
    foreach($input as $key=>$value){
        $value = mysql_real_escape_string($value);
        if($i === 0){
            $query .= '(`'.$key.'`, ';
            $values .= '("'.$value.'", ';
        }elseif($i === count($input) -1){
            $query .= '`'.$key.'`)';
            $values .= '"'.$value.'")';
        }
        else{
            $query .= '`'.$key.'`,';
            $values .= '"'.$value.'", ';
        }
        $i++;
    }
    $query .= ' VALUES'.$values;
    mysql_query($query);
}


function product_update($input)
{
    global $memcache;
    product_validate_input($input);
    $key = 'product:'.$input['id'];
    $memcache->delete($key);
    $where = ' WHERE id='.intval($input['id']);
    unset($input['id']);
    $query = 'UPDATE products SET ';
    $i = 0;
    foreach($input as $key => &$value){
        $value = mysql_real_escape_string($value);
        if($i === count($input) - 1){
            $query .= '`'.$key.'` = "'.$value.'"';
        }else{
            $query .= '`'.$key.'` = "'.$value.'", ';
        }
        $i++;
    }
    $query .= $where;
    if(mysql_query($query) !== false){
        $memcache->set($key, $input);
    }
}


/**
 * Delete product from database by it's ID;
 *
 * @param $id
 */
function product_delete($id)
{
    $id = intval($id);
    mysql_query('DELETE from products WHERE id='.$id);
}


/**
 * Validate product, renders error if validation fails
 * @param array $input
 */
function product_validate_input(array $input)
{
    foreach(product_attributes() as $name => $type){
        if(!array_key_exists($name, $input))
            renderError('Упс, кажется, ты забыл заполнить поле '.$name);

        if(!call_user_func('is_'.$type, $input[$name]))
            renderError('Ой, поле ' . $name . 'должно иметь тип ' . $type);
    }
}

/**
 * Find product by primary key(which is currently id)
 * and return array with it's attributes and values if found
 * and null otherwise
 *
 * @param string|integer $pk
 * @return array|null
 */
function product_find_by_pk($pk)
{
    global $memcache;
    if($result = $memcache->get('product:'.$pk))
        return $result;

    $query = 'SELECT * FROM products WHERE id='.intval($pk);
    $result = query($query);
    if(empty($result))
        return null;
    $memcache->set('product:'.$pk, $result[0]);
    return $result[0];
}


function product_make_pager_link($page, $params, $product, $type = LINK_NEXT)
{
    switch($type){
        case LINK_NEXT:
            $url = '/ajax-list/' . (intval($page) + 1).'?'.makePagerQueryString().'&last='.$product['id'];
            break;

        case LINK_PREV:
            $url = '/ajax-list/' . (intval($page) + -1).'?'.makePagerQueryString().'&first='.$product['id'];
            break;
    }
    if(array_key_exists('sort', $params) && $params['sort'] === 'price'){
        switch($type){
            case LINK_NEXT:
                $url .= '&lastPrice='.$product['price'];
                break;

            case LINK_PREV:
                $url .= '&firstPrice='.$product['price'];
                break;
        }
    }
    return $url;
}