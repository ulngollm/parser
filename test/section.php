<?php
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
include_once(ROOT . '/utils/autoload.php');
include_once(ROOT . '/utils/stat.php');

//---------------------------------------------

// 1 parser init
$url = 'https://www.home-heat.ru/catalog/';
$sections = array();
$xpath = array(
    'item' => '//section[@class="list_products"]//div[@class="container"]/ul/li[position()>1]',
    'link' => './a/@href',
    'name' => './a/span[@class="tizer-name"]/text()'
);
test_section_parser($url, $xpath, $sections);
unset($url, $sections, $xpath);

//---------------------------------------------

//2 parser init
$url = 'https://www.home-heat.ru/catalog/trubchatye-radiatory/';
$sections = array();
$xpath = array(
    'item' => '//div[@class="products_list"]/div[@class="pr_list-item"]',
    'link' => './/a[@class="list_item-name"]/@href',
    'name' => './/a[@class="list_item-name"]/text()',
);
test_section_parser($url, $xpath, $sections);

//---------------------------------------------

function test_section_parser($url, $xpath, &$sections)
{
    $parser = new SectionParser($url);
    $parser->get_section_list($xpath, $sections); //section_list not recursive
    SectionParser::remove_dom_nodes($sections);
    
    print_r(array_column($sections, 'name'));
    echo 'total count:'.count($sections);
}


