<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/functions.php');


// $url = 'https://www.home-heat.ru/catalog/trubchatye-radiatory-kzto/trubchatye-radiatory-kzto-paralleli-g-1034-mm-shirinoy/';
// $id = 90883;
// $url = 'https://www.home-heat.ru/catalog/trubchatye-radiatory-kzto/trubchatye-radiatory-kzto-paralleli-g-1534-mm-shirinoy/';
// $id = 90885;
$url = 'https://www.home-heat.ru/catalog/trubchatye-radiatory-kzto/trubchatye-radiatory-kzto-paralleli-g-1734-mm-shirinoy/';
$id = 90886;

$xpath = array(
    'name' => '//h1[@class="header_title"]',
    'price' => '//div[@class="descr_product-price"]',
    'img' => '//ul[@id="pr_slider-hor-items"]/li[not(@id="videoBox")]//a/@href',
    'desc_exclude' => '//div[@class="product_about"]//ul[@class="table-of-contents"]',
    'desc' => '//div[@class="product_about"]/div[@class="row"]/div',
    'complex' => array(
        'desc' => '//div[@class="description"]/div[@class="row"]/div[position()=1]',
        'img' => '//ul[@id="content_slider-items"]/li/img/@src'
    ),
    'props' => array(
        'item' => '//div[@class="product_about"]//ul[@class="table-of-contents"]/li',
        'name' => './/i[@class="feature_name"]/text()',
        'tooltip' => './/span[@class="feature_tooltip-text"]',
        'value' => './/i[@class="feature_value"]'
    )
);

$parser =  new Offer($url);
Logger::show_progress('x');
$offers = array();
$element = array();
$catalog = array(
    'model'=>&$element,
    'offers'=>&$offers
);
$element['desc'] = $parser->get_description($xpath['complex']['desc']);
get_offers_list($parser, $id, $offers);
set_common_props(current($offers), $element, $xpath['props'], $xpath['img'] );
print(count($offers).PHP_EOL);
unset($parser);

foreach($offers as &$offer){
    Logger::show_progress("-");
    $parser = new Offer(BASE_URL. $offer['link']);
    Logger::show_progress(".");
    $offer['name'] = $parser->get_name($xpath['name']);
    $offer['price'] = $parser->get_price($xpath['price']);
    $offer['img'] = $parser->get_images($xpath['img']);
    $offer['props'] = $parser->get_properties($xpath['props']);
    $offer['desc'] = $parser->get_description($xpath['desc'], $xpath['desc_exclude']);
    Utils::save_progress($catalog);
    Logger::show_progress("*");
}




