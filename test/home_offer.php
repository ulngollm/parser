<?php
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
$parser_name = 'home_offer_test';
include_once(ROOT . '/utils/stat.php');
include_once(ROOT . '/utils/autoload.php');
$url = 'https://www.home-heat.ru/catalog/trubchatye-radiatory-guardo-pilon-s4h-s-kruglym-profilem/trubchatyy-radiator-guardo-pilon-dvukhtrubnyy-200-mm-vysotoy/';
$params = array(
    'offer_link'=>'//td[@class="pr_color-name"]',
    'desc'=>'',
    'name'=>'//h1[@class="header_title"]'
);
$offer = new Parser($url);
// $offer->get_name($params['name']);
// $offer->get_offers_list($params['offer_link']);
print_r($offer->offers);
https://www.home-heat.ru/local/templates/main/components/bitrix/catalog/.default/dvs/catalog.element/.default/ajax.php?itemId=230179