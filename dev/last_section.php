<?php
include_once('detail.php');
define('DOMAIN', 'https://andimart.ru');

$url = 'https://andimart.ru/catalog/kanalizatsiya/soputstvuyushchie_tovary_dlya_kanalizatsii/metallokhomuty_dlya_kanalizatsii/';

libxml_use_internal_errors(true);
$elements = array();
get_offers_list($url, $elements);

function get_offers_list(string $url, array &$list,  int $page = 1)
{
    $page_url = "$url?PAGEN_1=$page";
    print("$page_url\n");
    $file = file_get_contents($page_url);
    $elem_page = new DOMDocument();
    $elem_page->loadHTML($file);
    $xpath = new DOMXPath($elem_page);
    $offers = $xpath->query("//div[@class='catalog__title']/a/@href");
    foreach ($offers as $offer) {
        $elem = $offer->nodeValue;
        array_push($list, $elem);
    }
    if ($page == 1) {
        $total_elem_count = $xpath->query("//span[@class='count__val']");
        $total_elem_count = $total_elem_count->item(0)->nodeValue;
        print("$total_elem_count\n");
        $elem_count =  $offers->length;
        if ($total_elem_count > $elem_count) {
            $pages_count = ceil($total_elem_count / $elem_count);
            for ($i = 2; $i <= $pages_count; $i++) {
                get_offers_list($url, $list, $i);
            }
        }
    }
}
print_r($elements);

$code = md5('Металлохомуты для канализации');
$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true;
$root = $xml->createElement('catalog');
$xml->appendChild($root);
$categories = $xml->createElement('categories');
$root->appendChild($categories);
$offers = $xml->createElement('offers');
$root->appendChild($offers);

foreach ($elements as $element) {
            $url = DOMAIN . $element;
            $offer = new Offer($url, $code, $xml);
            $offer_xml = $offer->get_xml();
            $offers->appendChild($offer_xml);
            $xml->save('offer_mh.xml');
}
