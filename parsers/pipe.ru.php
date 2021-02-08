<?php

error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/../log/eipower.log');

include_once(__DIR__ . "/../classes/class.php");
include_once(__DIR__ . '/../classes/xml.php');
include_once(__DIR__ . '/../classes/detail.php');
include_once(__DIR__ . '/../dev/utils.php');


// $url = 'https://pipl24.ru/';

// $section_params = array(
//     'section' => '///nav[@id="site-navigation"]/ul[@id="primary-menu"]/li[position()>3]/a',
//     'link' => './@href',
//     'text' => './@title'
// );
// $subsection_params = array(
//     'elements' => '//ul[contains(@class,"products")]//a[contains(@class, "woocommerce-loop-product__link")]/@href'
// );
$detail_params = array(
    'name' => '//h1',
    'section_path' => '//nav[@class="woocommerce-breadcrumb"]/a[position()>1]',
    'images' => '//div[contains(@class,"product-images")]//div[contains(@class, "slider-for")]//img[@class="lazyload"]/@src',
    'preview' => '//div[@class="short-description"]/div[@class="woocommerce-product-details__short-description"]/node()',
    'desc' => '//div[@id="tab-description"]/div[@class="tab-content"]',
    'props' => '//table[contains(@class,"shop_attributes")]/tbody/tr',
    'price' =>  '//div[@class="head-product"]//p[@class="price"]/span[contains(@class,"woocommerce-Price-amount")]/bdi/text()',
    'article' => '//div[contains(@class,"articul_text")]//span[@class="sku"]',
    'brand' => '//table[contains(@class, "shop_attributes")]//tr[contains(@class,"woocommerce-product-attributes-item--attribute_pa_brand")]/td[@class="woocommerce-product-attributes-item__value"]//text()'
);
$exclude_brands = array('Vimtag','Ivideon',' Tiandy','Nobelic','BAS-IP','CTV',
'ДАКСИС','Cyfral');
log_parser_start('eipower'); //debug

// $parser = new SectionParser($url, $section_params);


// $sections = $parser->get_section_list();
// show_progress(); //debug
// unset($parser);
// print(count($sections));


// foreach ($sections as &$section) {
//     // $xml->add_category($section);
//     $parser = new SectionParser($section['link'], $subsection_params);
//     $section['elements'] = $parser->get_elements_list();
//     // print_r($section['elements']);

//     $page_count = $parser->query('//form[@class="pagination-top"]/input[@type="number"]/@max')->item(0)->nodeValue;
//     if ($page_count > 1) {
//         unset($parser);
//         for ($i = 2; $i <= $page_count; $i++) {
//             $subparser = new SectionParser($section['link'] . "page/$i/", $subsection_params);
//             $page_elems = $subparser->get_elements_list();
//             $all_elems = $section['elements'];
//             $section['elements'] = array_merge($all_elems, $page_elems);
//             show_progress();
//         }
//         show_progress("*");
//     }
// }
// print(count($sections));

// save_json($sections, 'pipl.json'); //debug

$file = file_get_contents(__DIR__."/../output/pipl.json");
$sections = json_decode($file, true);
// print_r($sections);

$xml = new XMLGenerator();
foreach ($sections as $section) {
    if (isset($section['elements'])) {
        foreach ($section['elements'] as $elem) {
            $offer = new Offer($elem, $detail_params);
            if(!in_array($offer->brand, $exclude_brands)){
                $offer->set_section_path();
                $offer->set_preview();
                $xml->add_offer($offer);
                $xml->xml->save(__DIR__ . '/../output/eipower_last.xml');
            }
            show_progress(); //debug
        }
        show_progress('*');
    }
    show_progress('|');
}

log_parser_end('eipower');
