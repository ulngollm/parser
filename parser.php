<?php
define('DOMAIN', 'https://andimart.ru');
include_once('detail.php');
$str = file_get_contents('structure.json');
$sections = json_decode($str, true);
//для конвертации из sdtClass при десериализации указать второй параметр true

$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true;
$root = $xml->createElement('catalog');
$xml->appendChild($root);
$categories = $xml->createElement('categories');
$root->appendChild($categories);
$offers = $xml->createElement('offers');
$root->appendChild($offers);

echo memory_get_usage(true) . "\n";
foreach ($sections as $section) {
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
            print_r($url);
            $offer = new Offer($url, $categoryID, $xml);
            $offer_xml = $offer->get_xml();
            $offers->appendChild($offer_xml);
        }
    }
}
echo memory_get_peak_usage(true) / 1024;

$xml->save('offers.xml');
