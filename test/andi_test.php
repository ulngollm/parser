<?php
include_once(__DIR__ . '/../classes/xml.php');
include_once(__DIR__ . '/../classes/class.php');
include_once(__DIR__ . '/../classes/detail.php');
include_once(__DIR__ . '/../dev/utils.php');


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
$detail
$url = 'https://andimart.ru';

$parser = new SectionParser($url, $root_section_params);

$root_sections = $parser->get_section_list();
$all_sections = $root_sections;

foreach ($root_sections as $root_section) {
    $sections = $parser->get_section_list($section_params, $root_section);
    $all_sections = array_merge($all_sections, $sections);
    foreach ($sections as $section) {
        $subsections = $parser->get_section_list($subsection_params, $section);
        foreach ($subsections as &$subsection) {
            $subsection['elements'] = $parser->get_elements_list($subsection_params['elements']);
        }
        $all_sections = array_merge($all_sections, $subsections);
    }
    print_r(count($subsections));
}
// foreach ($all_sections as &$section) {
//     unset($section['node']);
//     $section['link'] = $url . $section['link'];
// }
save_json($all_sections, 'sectins_andi.json'); //debug

foreach($all_sections as $section){
    if (isset($section['elements'])) {
        foreach ($section['elements'] as $link_element) {
            $link_element = $url . $link_element;
            $offer = new Offer($link_element, $de)
        }
    }
}
