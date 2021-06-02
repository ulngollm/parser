<?php
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
include_once(ROOT . '/autoload.php');
$filename = 'kzto_catalog.json';

$catalog = Utils::load_from_json($filename, false);
$xml = new XMLGenerator();
$xml->offers_list_from_array($catalog['model']);
$xml->offers_list_from_array($catalog['offers']);
$xml->save_xml('kzto');
