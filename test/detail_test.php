<?php

include_once('./classes/xml.php');
include_once('./classes/class.php');
include_once('./classes/detail.php');

$params = array(
    'name' => '//h1[@class="ty-product-block-title"]/bdi/text()',
    'images' => '//div[contains(@id, "product_images")]/div/div[@class="ty-product-img cm-preview-wrapper"]/a/@href',
    'desc'=>'//div[@id="content_description"]/div',
    'props'=>'//div[@id="content_features"]//div[@class="ty-product-feature"]',
    'price'=>'//div[@class="ty-product-block__price-actual"]//span[@class="ty-price-num"][1]',
    'article'=>'//div[@class="ty-product-block__sku"]/div/span',
);

$parent_code = 'gjvjgchadasco';

$url = "https://daichi-aircon.ru/nastennye-split-sistemy-daichi/daichi-da25evq1-df25ev1/";
$offer = new Offer($url, $parent_code, $params);
print_r($offer);

$xml = new XMLGenerator();
$xml->add_offer($offer);
$xml->xml->save('./output/one.xml');

