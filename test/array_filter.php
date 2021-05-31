<?php


$arr = array(
    '123'=>array(
        'type'=>1
    ),
    '126'=>array(
        'type'=>2
    ),
    '127'=>array(
        'type'=>1
    ),
    '12234'=>array(
        'type'=>0
    ),
    '1298'=>array(
        'type'=>1
    ),
    '1223'=>array(
        'type'=>3
    ),
    '125'=>array(
        'type'=>0
    ),
);
$sorted = array_filter($arr, function($value){
    return $value['type'] == 1;
});

print_r($sorted);