<?php
$array1 = [1, 2, 3, 4, 5, 6, 7];
$array2 = ['xtet', 'okla', 'asoknv', 'apou23', null];

$array3 = array(
    'teft' => array(
        'link' => 54,
        'section' => 452
    )
);
$array4 = array(
    'teft' => array(
        'link' => 54,
        'section' => 484
    )
);

$res = array_merge($array3, $array4);
print_r($res);
