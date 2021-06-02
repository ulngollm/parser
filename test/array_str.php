<?php

$arr = array(
    "props" => array(
        'NAME' => 'dsdxsa',
        'GIDS' => 252,
        'SEA' => 'DS'
    )
);
foreach($arr['props'] as $prop_code=>&$prop_value){
    $prop_value = array(
        'code'=>$prop_code,
        'value'=>$prop_value
    );
}
print_r($arr);
