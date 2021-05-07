<?php
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
$parser_name = 'home_detail_test';
include_once(ROOT . '/utils/stat.php');
include_once(ROOT . '/utils/autoload.php');
include_once(ROOT . '/utils/format.php');
$base_url = "https://www.home-heat.ru";
$offer_url = "https://www.home-heat.ru/catalog/termostaticheskie-golovki-hummel/programmiruemyy-termoregulyator-dlya-radiatorov-hummel-safedrive-m30x1-5-belyy-r-2909-0000-95/";
$param = array(
    'name'=>'//h1[@class="header_title"]',
    'price'=>'//div[@class="descr_product-price"]',
    'img'=>'//ul[@id="pr_slider-hor-items"]/li[not(@id="videoBox")]//a/@href',
    'props'=>'//div[@class="product_about"]//ul[@class="table-of-contents"]/li',
    'props_container'=>'//div[@class="product_about"]//ul[@class="table-of-contents"]',
    'desc'=>'//div[@class="product_about"]/div[@class="row"]/div'
);

$offer = new Offer($offer_url);
function get_all_offer_data(Offer $offer, array $param){
    $offer->get_name($param['name']);
    $offer->get_price($param['price']);
    $offer->get_properties($param['props']);
    $offer->get_images($param['img']);
    $props_node = $offer->query($param['props_container'])->item(0);
    $offer->get_description($param['desc'], $props_node);
    $offer->description = remove_empty_tags(remove_symbols(remove_attr($offer->description)));
}
$offer->test = 'asdasd';
get_all_offer_data($offer, $param);
print_r($offer);