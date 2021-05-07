<?php
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
$parser_name = 'home_offer_test';
include_once(ROOT . '/utils/stat.php');
include_once(ROOT . '/utils/autoload.php');
include_once(ROOT . '/utils/format.php');
$base_url = "https://www.home-heat.ru";
$url = 'https://www.home-heat.ru/catalog/trubchatye-radiatory-guardo-pilon-s4h-s-kruglym-profilem/trubchatyy-radiator-guardo-pilon-dvukhtrubnyy-200-mm-vysotoy/';
$params = array(
    'base_offers_url'=>"$base_url/local/templates/main/components/bitrix/catalog/.default/dvs/catalog.element/.default/ajax.php?itemId=%d",
    'offer_link'=>'//td[@class="pr_color-name"]',
    'desc'=>'',
    'name'=>'//h1[@class="header_title"]'
);
$offer = new ComplexOffer($url, $params, 123,230179);
$offer->get_offers_list($params['base_offers_url']);
print_r($offer->offers);


// //выделить id из bx_123_123
// //выпарсить ссылку из тега в json

// $page = file_get_contents('https://www.home-heat.ru/local/templates/main/components/bitrix/catalog/.default/dvs/catalog.element/.default/ajax.php?itemId=230179');
// $data = json_decode($page, true);
// foreach($data as $item){
//     $link = extract_href($item['name']);
//     print($link);
//     break;
// }
