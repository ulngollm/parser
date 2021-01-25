<?php
$xml = new DOMDocument('1.0', 'utf-8');
$xml->formatOutput = true;
$root = $xml->createElement('offers');
$xml->appendChild($root);
function get_detail_xml($xml)
{
    $goodsName = $xml->createElement('name', $name);
    $goods = $xml->createElement('offer');

    $imgs = $xml->createElement('images');
    foreach ($images_list as $image) {
        $img = $xml->createElement('image', $image);
        $imgs->appendChild($img);
    }
    $root->appendChild($goods);
    $goods->appendChild($goodsName);
    $goods->appendChild($imgs);

    $props = $xml->createElement('properties');
    foreach ($property_list as $property_elem) {
        $prop = $xml->createElement('property');
        $propName = $xml->createElement('propName', $property_elem[0]);
        $propValue = $xml->createElement('propValue', $property_elem[1]);
        $prop->appendChild($propName);
        $prop->appendChild($propValue);
        $props->appendChild($prop);
    }
    $goods->appendChild($props);

    $desc = $xml->createElement('description', $description);
    $goods->appendChild($desc);

    $priceValue = $xml->createElement('price', trim($price));
    $goods->appendChild($priceValue);

    $CML2article = $xml->createElement('article', trim($article));
    $goods->appendChild($CML2article);

    $developer = $xml->createElement('brand', trim($brand));
    $goods->appendChild($developer);
}



$xml->save('offers.xml');
