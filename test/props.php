<?php
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
$url = "https://www.home-heat.ru/catalog/reshetki-dlya-vnutripolnykh-konvektorov-itermic/reshetki-derevyannye-dub-sgwz-tsvet-venge-dlya-vnutripolnykh-konvektorov-itermic/reshetka-dlya-vnutripolnogo-konvektora-itermic-grill-sgwz-20-600-shag-13-mm-derevyannaya-dub-tsvet-venge/";

include_once(ROOT . '/autoload.php');

$props = [
    'item'=>'//div[@class="product_about"]//ul[@class="table-of-contents"]/li',
    'name'=>'.//i[@class="feature_name"]',
    'value'=>'.//i[@class="feature_value"]'
];
$parser = new Offer($url);
$props = $parser->get_properties($props);
print_r($props);
