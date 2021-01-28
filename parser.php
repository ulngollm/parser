<?php
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/parser_log.log');
define('DOMAIN', 'https://andimart.ru');
include_once('detail.php');
libxml_use_internal_errors(true);
$str = file_get_contents('structure.json');
$sections = json_decode($str, true); //для конвертации из sdtClass в array при десериализации указать второй параметр true

$offers_count = 0;
foreach ($sections as $section) {
    if (isset($section['elements']))
        $offers_count += count($section['elements']);
}
$current_offer = 0;


$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true;
$root = $xml->createElement('catalog');
$xml->appendChild($root);
$categories = $xml->createElement('categories');
$root->appendChild($categories);
$offers = $xml->createElement('offers');
$root->appendChild($offers);

file_put_contents('parser_log.log', date("Y-m-d H:i:s") . " Для скрипта запрошено " . memory_get_usage(true) / 1024 . "Кб памяти.\n", FILE_APPEND);
foreach ($sections as $section) {
    $code = $section['code'];
    $category = $xml->createElement('category', $section['name']);
    $categories->appendChild($category);
    $category_code = $xml->createAttribute('id');
    $category_code->value = $section['code'];
    $category->appendChild($category_code);
    if (isset($section['parent_code'])) {
        $parent_category_code = $xml->createAttribute('parentId');
        $parent_category_code->value = $section['parent_code'];
        $category->appendChild($parent_category_code);
    }
    if (isset($section['elements'])) {
        foreach ($section['elements'] as $url) {
            $url = DOMAIN . $url;
            file_put_contents('parser_log.log', "$current_offer $url\n", FILE_APPEND);
            $offer = new Offer($url, $code, $xml);
            $offer_xml = $offer->get_xml();
            $offers->appendChild($offer_xml);
            $current_offer++;
            show_status($current_offer, $offers_count);
        }
    }
    file_put_contents('parser_log.log', "Максимально выделенное количество памяти " . memory_get_peak_usage(true) / 1024  . "Кб\n", FILE_APPEND);
    $xml->save('offers.xml');
}
file_put_contents('parser_log.log', date("Y-m-d H:i:s") ." Парсинг $offers_count товаров выполнен успешно\n", FILE_APPEND);

function show_status($current, $total)
{
    system('clear');
    echo "$current товаров из $total завершено";

}
