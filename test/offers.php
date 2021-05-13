<?php
const ROOT = '/home/ully/Документы/parser/catalog_parser';
include_once(ROOT . '/utils/autoload.php');
include_once(ROOT . '/utils/stat.php');

// $url = 'https://www.home-heat.ru/catalog/panelnye-radiatory-s-gorizontalnymi-nasechkami/';//раздел с 4 товарами, удобно для теста
// $url = 'https://www.home-heat.ru/catalog/trubchatye-radiatory-guardo-retta-6p-s-pryamougolnym-profilem/';// с товарами guardo - проверка исключеия бренда
$url = 'https://www.home-heat.ru/catalog/vse-vertikalnye-radiatory/?PAGEN_1=7';//c спростыми товарами
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
$parser = new OffersParser($url,$elements, 'aascujli36');
$parser->get_elements_list($xpath,'get_offer_type');
print_r($elements);


//callback для определения типа товара
//можно встроить любую свою фукнцию
function get_offer_type(Parser $parser, DOMNode $element, string $xpath){
    $class_attr = $parser->parse_single_value($xpath, $element);//парсим класс
    if(strpos($class_attr, 'single-product')) return OfferType::SIMPLE;
    else return OfferType::COMPLEX;
}