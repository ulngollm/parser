<?php
const ROOT = '/home/ully/Документы/parser/catalog_parser';
include_once(ROOT . '/utils/autoload.php');
include_once(ROOT . '/utils/stat.php');

$url = 'https://www.home-heat.ru/catalog/panelnye-radiatory-s-gorizontalnymi-nasechkami/';
// $url = 'https://www.home-heat.ru/catalog/trubchatye-radiatory-guardo-retta-6p-s-pryamougolnym-profilem/';
$elements = array();
$xpath = array(
    'item'=>'//div[contains(@class,"pr_list-item")]',
    'id' => './@id',
    'link' => './a[@class="list_item-name"]/@href',
    'name' => './a[@class="list_item-name"]/text()',
    'class' => './@class',
    'next_page' => '//ul[@class="pagination"]/li[@class="ax-pag-next"]/a/@href'
);
$exclude_brand = 'guardo';
$parser = new OffersParser($url,$elements);
$parser->get_elements_list($xpath);
print_r($elements);



// function get_offer_type($parser, $element, $xpath = null){
//     $class_attr = $parser->parse_single_value($xpath['class'], $element);//парсим класс
//     print_r($class_attr);
// }