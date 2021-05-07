<?php
$parser_name = 'home_hit_1';
$base_url = 'https://www.home-heat.ru';

const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
include_once(ROOT . '/utils/autoload.php');
include_once(ROOT . '/utils/stat.php');
include_once(ROOT. '/dev/home/home-heat-class.php');

HomeHeatParser::init($base_url, $parser_name);
HomeHeatParser::getParentSection();
print_r(HomeHeatParser::$sections);