<?php
include_once(__DIR__ . '/../config.php');

// $elements = Utils::load_from_json('output/elem.json') ?? die('File is not exist');
// $names = array_column($elements, 'name', 'id');
// sort($names);
// print_r($names);
// Utils::save_json($names, 'test/names.json');

$elements = Utils::load_from_json('test/names.json') ?? die('File is not exist');
array_splice($elements, 0, 4500);
$elem_count = count($elements);
for($i = 0; $i < $elem_count; $i+=2){
    $str1 = explode(' ', $elements[$i]);
    $str2 = explode(' ',$elements[$i+1]);
    print_r(array_intersect($str1, $str2));
    print_r(array_diff($str1, $str2));
    echo PHP_EOL;

}

