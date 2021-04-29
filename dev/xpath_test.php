<?php
include_once('detail.php');
define('DOMAIN', 'https://andimart.ru');
$url = 'https://andimart.ru/catalog/otoplenie/kotelnaya_komnata/aksessuary_dlya_kotlov/nadstavka_polu_turbo_pt_50/';
$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true;
$root = $xml->createElement('catalog');
$xml->appendChild($root);
$categories = $xml->createElement('categories');
$root->appendChild($categories);
$offers = $xml->createElement('offers');
$root->appendChild($offers);
$code = 123;

$offer = new Offer($url, $code, $xml);
$offer_xml = $offer->get_xml();
$offers->appendChild($offer_xml);
echo memory_get_peak_usage(true) / 1024;

$xml->save('offer_alone.xml');