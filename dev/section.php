<?php
include_once(__DIR__.'/../classes/xml.php');
include_once(__DIR__.'/../classes/class.php');
include_once(__DIR__.'/../classes/detail.php');

$detail_params = array(
    'name' => '//h1[@class="ty-product-block-title"]/bdi/text()',
    'images' => '//div[contains(@id, "product_images")]/div/div[@class="ty-product-img cm-preview-wrapper"]/a/@href',
    'desc' => '//div[@id="content_description"]/div/p[text()]',
    'props' => '//div[@id="content_features"]//div[@class="ty-product-feature"]',
    'price' => '//div[@class="ty-product-block__price-actual"]//span[@class="ty-price-num"][1]',
    'article' => '//div[@class="ty-product-block__sku"]/div/span',
);

$json = '{
    "name": "Напольно-потолочные",
    "code": "e6bb2e9e9802122a96b40f8578392340",
    "link": "https:\/\/daichi-aircon.ru\/tehnologicheskogo-ohlazhdeniya\/napolno-potolochnye\/",
    "parent_code": "7d68f9657bdc57254ca4e9d01a87e838",
    "elements": [
        "https:\/\/daichi-aircon.ru\/tehnologicheskogo-ohlazhdeniya\/daichi-dat100alks1-dft100als1-40\/",
        "https:\/\/daichi-aircon.ru\/tehnologicheskogo-ohlazhdeniya\/daichi-dat140alks1-dft140als1-40\/",
        "https:\/\/daichi-aircon.ru\/tehnologicheskogo-ohlazhdeniya\/daichi-dat160alks1-dft160als1-40\/",
        "https:\/\/daichi-aircon.ru\/tehnologicheskogo-ohlazhdeniya\/daichi-dat70alks1-dft70als1\/",
        "https:\/\/daichi-aircon.ru\/tehnologicheskogo-ohlazhdeniya\/daichi-dat100alks1-dft100als1\/",
        "https:\/\/daichi-aircon.ru\/tehnologicheskogo-ohlazhdeniya\/daichi-dat140alks1-dft140als1\/",
        "https:\/\/daichi-aircon.ru\/tehnologicheskogo-ohlazhdeniya\/daichi-dat160alks1-dft160als1\/",
        "https:\/\/daichi-aircon.ru\/tehnologicheskogo-ohlazhdeniya\/daichi-dat70alks1-dft70als1-40\/"
    ]
}';
$url = 'https://daichi-aircon.ru/tehnologicheskogo-ohlazhdeniya/napolno-potolochnye/';
$xml = new XMLGenerator();
$section = json_decode($json, true);
$parent_code = $section['code'];
foreach ($section['elements'] as $elem) {
    $offer = new Offer($elem, $parent_code, $detail_params);
    $xml->add_offer($offer);
}
$xml->xml->save(__DIR__.'/../output/test.xml');
