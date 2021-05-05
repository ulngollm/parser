<?php
include_once(__DIR__ . '/../classes/xml.php');
include_once(__DIR__ . '/../classes/class.php');
include_once(__DIR__ . '/../classes/detail.php');
include_once(__DIR__ . '/../dev/utils.php');

error_reporting(E_ALL);
ini_set('error_log', __DIR__ . '/log/andimart.log');

$root_section_params = array(
    'path' => '//ul[@class="nav__list"]/li[position()<4]',
    'link' => './a/@href',
    'name' => './a/span/text()',
);
$section_params = array(
    'path' => './div[@class="nav__child"]//ul[@class="nav__child-group"]',
    'link' => './li[contains(@class,"nav__child-item--heading")]/a/@href',
    'name' => './li[contains(@class,"nav__child-item--heading")]/a/text()',
);
$subsection_params = array(
    'path' => './li[position()>1]/a',
    'link' => './@href',
    'name' => './text()',
    'elements' => '//div[@class="catalog__title"]/a/@href',
);
$detail_params = array(
    'name' => '//h1',
    'images' => '//div[@class="product-card__image"]/a/@href',
    'desc' => '//div[@id="product-descr"]//div[contains(@class, "wysiwyg")]/p',
    'props' => '//table[@class="props"]//tr[@class="props__item"]',
    'price' =>  '//div[@class="product-card__price"]/text()[3]',
    'article' => '//div[@class="product-card__prop"][1]/text()[2]',
    'brand' => '//div[@class="product-card__prop"][2]/text()[2]'
);
$url = 'https://andimart.ru';

// $parser = new SectionParser($url, $root_section_params);
$xml = new XMLGenerator();
log_parser_start('andimart'); //debug

// $root_sections = $parser->get_section_list();
// $all_sections = $root_sections;

// foreach ($root_sections as $root_section) {
//     $sections = $parser->get_section_list($section_params, $root_section);
//     $all_sections = array_merge($all_sections, $sections);
//     show_progress($root_section['name'] . " ");
//     foreach ($sections as $section) {
//         show_progress($section['name'] . "\n");
//         $subsections = $parser->get_section_list($subsection_params, $section);
//         foreach ($subsections as &$subsection) {
//             $elements = array();
//             $subparser = new SectionParser($url . $subsection['link'], $subsection_params);
//             $elements = $subparser->get_elements_list();

//             $elem_count = $subparser->query($subparser->xpath['elements'])->length;
//             $total_elem_count = intval($subparser->query('//div[@class="catalog-ctrls__in"]//span[@class="count__val"]')->item(0)->nodeValue);
//             if ($total_elem_count > $elem_count) {
//                 unset($subparser);
//                 $pages_count = ceil($total_elem_count / $elem_count);
//                 for ($i = 2; $i <= $pages_count; $i++) {
//                     $page_parser = new SectionParser($url . $subsection['link']."?PAGEN_1=$i", $subsection_params);
//                     $page_elements = $page_parser->get_elements_list();
//                     $elements = array_merge($elements, $page_elements);
//                 }
//             }

//             $subsection['elements'] = $elements;
//         }
//         $all_sections = array_merge($all_sections, $subsections);
//     }

//     print_r(count($subsections));
// }

// save_json($all_sections, 'sectins_andi.json'); //debug

$json = file_get_contents(__DIR__ . '/../output/sectins_andi.json');
$all_sections = json_decode($json, true);
foreach ($all_sections as $section) {
    $xml->add_category($section);
    show_progress($section['name']);
    $parent_code = $section['code'];
    if (isset($section['elements'])) {
        foreach ($section['elements'] as $link_element) {
            $link_element = $url . $link_element;
            $offer = new Offer($link_element, $detail_params, $parent_code);
            $xml->add_offer($offer);
            show_progress(); //debug
            $xml->xml->save(__DIR__ . '/../output/andimart.xml');
        }
    }
}
log_parser_end('andimart');