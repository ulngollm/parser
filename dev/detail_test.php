<?php

include_once('./classes/xml.php');
include_once('./classes/class.php');
include_once('./classes/detail.php');

$params = array(
    'name' => '//h1',
    'section_path'=>'//nav[@class="woocommerce-breadcrumb"]/a[position()>1]',
    'images' => '//div[contains(@class,"product-images")]//div[contains(@class, "slider-for")]//img[@class="lazyload"]/@src',
    'preview' => '//div[@class="short-description"]/div[@class="woocommerce-product-details__short-description"]/node()',
    'desc' => '//div[@id="tab-description"]/div[@class="tab-content"]',
    'props' => '//table[contains(@class,"shop_attributes")]/tbody/tr',
    'price' =>  '//div[@class="head-product"]//p[@class="price"]/span[contains(@class,"woocommerce-Price-amount")]/bdi/text()',
    'article' => '//div[contains(@class,"articul_text")]//span[@class="sku"]',
    'brand' => '//table[contains(@class, "shop_attributes")]//tr[contains(@class,"woocommerce-product-attributes-item--attribute_pa_brand")]/td[@class="woocommerce-product-attributes-item__value"]//text()'
);

$url = "https://pipl24.ru/product/ip-videodomofon-bas-ip-at-07l-silver/";
$offer = new Offer($url, $params);

print_r($offer);
$offer->set_section_path();
$offer->set_preview();
print_r($offer->section_path);
print_r($offer->description);


$xml = new XMLGenerator();
$xml->add_offer($offer);
$xml->xml->save('./output/onepipl.xml');

