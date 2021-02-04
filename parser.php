<?php
include_once('./classes/class.php');

$url = 'https://daichi-aircon.ru/';
$parser = new SectionParser($url, $section_params);
// $xml = new XMLGenerator();

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
$detail_params = array();

$sections = $parser->get_section_list($section_params);
print_r($sections);
unset($parser);

$section_list = array();
foreach ($sections as &$section) {
    $subparser = new SectionParser($section['link'], $subsection_params, $section['code']);
    $isCurrent = $subparser->query('//li[@class="ty-subcategories__current_item"]')->length; 

    if (!$isCurrent) {//если у раздела нет вложенных
        echo 1;
        $subsections = $subparser->get_section_list();
        $elements = $subparser->get_elements_list();
        if ($elements) $section['elements']  = $elements;
        $section_list = array_merge($section_list, $subsections);
    }
    else {
        $elements = $subparser->get_elements_list();
        if ($elements) $section['elements']  = $elements;
    }
}

foreach($section_list as &$section){
    if(!isset($section['elements'])){
        $subparser = new SectionParser($section['link'], $subsection_params, $section['code']);
        $section['elements'] = $subparser->get_elements_list();
    }
}
print_r(count($section_list));
save_json(array_merge($sections, $section_list));


function save_json(array $arr)
{
    $data = json_encode($arr, JSON_UNESCAPED_UNICODE);
    file_put_contents(__DIR__ . '/output/section.json', $data, FILE_APPEND);
}
