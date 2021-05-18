<?php
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
include_once(ROOT . '/autoload.php');

const PARSER_NAME = 'home_hit_debug';
$data_file = PARSER_NAME. "_catalog.json";
$catalog = Utils::load_from_json($data_file, false);

$id = 26536;

$elements = $catalog['offers'];
$offers = array();
foreach($elements as $elem){
    if(isset($elem['model']) && $elem['model'] == $id)
        array_push($offers, $elem);
} 
$props = array_column($offers, 'props');
foreach($props as &$prop){
    print_r(count($prop));
    $prop = array_column($prop, 'value', 'name');
}
// print_r($props);
// print_r(count($props[0]));
$arr = array_intersect($props[0], ...$props);
print_r($arr);
print_r(array_diff($props[0], $arr));