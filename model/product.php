<?php
/**
 * Created by PhpStorm.
 * User: hlogeon
 * Date: 7/3/15
 * Time: 5:49 AM
 */


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


function product_add($input)
{
    $query = 'INSERT INTO products';
    $values = '';
    $i = 0;
    foreach($input as $key=>$value){
        if($i === 0){
            $query .= '(`'.$key.'`, ';
            $values .= '("'.mysql_real_escape_string($value).'", ';
        }elseif($i === count($input) -1){
            $query .= '`'.$key.'`)';
            $values .= '"'.mysql_real_escape_string($value).'")';
        }
        else{
            $query .= '`'.$key.'`,';
            $values .= '"'.mysql_real_escape_string($value).'", ';
        }
        $i++;
    }
    $query .= ' VALUES'.$values;
    mysql_query($query);
}


function product_delete($id)
{
    $id = intval($id);
    mysql_query('DELETE from products WHERE id='.$id);
}


function product_all()
{

}


function product_find_by_pk($pk)
{

}