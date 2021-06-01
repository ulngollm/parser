<?php
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
$filename = 'one_catalog.json';

include_once(ROOT . '/autoload.php');
$catalog = Utils::load_from_json($filename, false);
$xml = new XMLGenerator();
$xml->offers_list_from_array($catalog['model']);
$xml->offers_list_from_array($catalog['offer']);
$xml->save_xml('one_elem');