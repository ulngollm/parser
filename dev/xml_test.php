<?php
include_once('./classes/xml.php');
$xml = new XMLGenerator();
$params = array(
    "name" => "Кассетные кондиционеры",
    "code" => "26861a29e18a5235e5092046f4c6d1ea",
    "link" => "https=>\/\/daichi-aircon.ru\/kassetnye-kondicionery-daichi\/",
    "parent_code" => null
);
$xml->add_category($params);
echo $xml->xml->saveXML();