<?php
include_once('detail.php');

$str = file_get_contents('structure.json');
$sections = json_decode($str, true);
//для конвертации из sdtClass при десериализации указать второй параметр true
$domain = 'https://andimart.ru';

$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true;
$root = $xml->createElement('catalog');
$xml->appendChild($root);
$categories = $xml->createElement('categories');
$root->appendChild($categories);
$offers = $xml->createElement('offers');
$root->appendChild($offers);

echo memory_get_usage(true)."\n";
foreach ($sections as $key=>$section) {
    $categoryID = $key;
    $category = $xml->createElement('category', $section['name']);
    $categories->appendChild($category);
    $category_code = $xml->createAttribute('id');
    $category_code->value = $categoryID;
    $category->appendChild($category_code);
    // $parent_category_code = $xml->createAttribute('parentId');
    // $parent_category_code->value = $sectionID;


    foreach ($section['elements'] as $url) {
        $url = $domain . $url;
        print_r($url);
        $offer = new Offer($url, $categoryID, $xml);
        $offer_xml = $offer->get_xml();
        $offers->appendChild($offer_xml);
    }
}
echo memory_get_peak_usage(true) / 1024;

$xml->save('offers.xml');
