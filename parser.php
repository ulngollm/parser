<?php
error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/log/daichi.log');

include_once(__DIR__ . "/classes/class.php");
include_once(__DIR__ . '/classes/xml.php');
include_once(__DIR__ . '/classes/detail.php');
include_once(__DIR__ . '/dev/utils.php'); 


$url = 'https://daichi-aircon.ru/';

$section_params = array(
    'section' => '//div[@id="dropdown_5106"]/ul/li[position()>1 and position() <10]/a[@href]',
    'link' => './@href',
    'text' => './div/text()'
);
$subsection_params = array(
    'section' => '//li[@class="ty-subcategories__item"]',
    'link' =>  './a/@href',
    'text' => './a/span/text()',
    'elements' => '//div[@class="ty-product-list__item-name"]/bdi/a/@href'
);
$detail_params = array(
    'name' => '//h1[@class="ty-product-block-title"]/bdi/text()',
    'images' => '//div[contains(@id, "product_images")]/div/div[@class="ty-product-img cm-preview-wrapper"]/a/@href',
    'desc' => '//div[@id="content_description"]/div/p[text()]',
    'props' => '//div[@id="content_features"]//div[@class="ty-product-feature"]',
    'price' => '//div[@class="ty-product-block__price-actual"]//span[@class="ty-price-num"][1]',
    'article' => '//div[@class="ty-product-block__sku"]/div/span',
);
log_parser_start('daichi'); //debug

$parser = new SectionParser($url, $section_params);
$xml = new XMLGenerator();

//получаем список верхних разделов
$root_sections = $parser->get_section_list();
show_progress(); //debug
unset($parser);

$section_list = array();
//проходимся по страницам верхних разделов
foreach ($root_sections as &$section) {
    $xml->add_category($section);
    $subparser = new SectionParser($section['link'], $subsection_params, $section['code']);
    //смотрим, есть ли товары на этой странице
    $elements = $subparser->get_elements_list();
    if ($elements) $section['elements']  = $elements;

    //проверяем, есть ли у раздела вложенные
    //если раздел отмечен в меню как текущий, значит, вложенных нет
    $isCurrent = $subparser->query('//li[@class="ty-subcategories__current_item"]')->length;
    if (!$isCurrent) {
        $subsections = $subparser->get_section_list();
        $section_list = array_merge($section_list, $subsections);
    }
    show_progress(); //debug
}

//получаем товары : проходим по страницам вложенных разделов 
foreach ($section_list as &$section) {
    $xml->add_category($section);
    //если это не те верхние разделы, в которых были элементы, получаем ссылки товаров
    if (!isset($section['elements'])) {
        $subparser = new SectionParser($section['link'], $subsection_params, $section['code']);
        $section['elements'] = $subparser->get_elements_list();
    }
    show_progress();
}
$xml->xml->save(__DIR__ . '/output/xml.xml');
$sections = array_merge($root_sections, $section_list);
save_json($sections);//debug
// пойти по всем товарам
$count = count($sections);
foreach ($sections as $key=>$section) {
    if (!isset($section['elements'])){
        print($section['name']." doesn't have elements. $key from $count\n");

    }
    // if (isset($section['elements'])) {
    //     $parent_code = $section['code'];
    //     foreach ($section['elements'] as $elem) {
    //         $offer = new Offer($elem, $parent_code, $detail_params);
    //         $xml->add_offer($offer);
    //         show_progress(); //debug
    //     }
    //     $xml->xml->save(__DIR__ . '/output/xml.xml');
    // }
}

log_parser_end();
