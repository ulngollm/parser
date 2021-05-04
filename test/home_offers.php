<?php
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
$parser_name = 'home_test_elem';
include_once(ROOT . '/utils/stat.php');
include_once(ROOT . '/utils/autoload.php');
$url = 'https://www.home-heat.ru/catalog/vse-vertikalnye-radiatory/?PAGEN_1=7';
$elem_params = array(
    'element_id' => '//div[contains(@class,"wa_catalog-section")]/div[contains(@class,"pr_list-item")]/@id',
    'link'=>'./a[@class="list_item-name"]/@href',
    'name'=>'./a[@class="list_item-name"]/text()',
    'class_single'=>'./self::node()[contains(@class, "single-product")]',
    'next_page' => '//ul[@class="pagination"]/li[@class="ax-pag-next"]/a/@href',
);
$elements = array();
$parser = new OffersParser($url, $elem_params, $elements, '');
$elements = $parser->get_elements_list();
save_json($elements, 'test_elem.json');
