<?php
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
$filename = 'home_hit_debug_catalog.json';

include_once(ROOT . '/autoload.php');

$xml = new XMLGenerator($filename);

$xml->save_xml('test');