<?php
const PARSER_NAME = 'home_hit_2';
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
const BASE_URL = 'https://www.home-heat.ru';

include_once(ROOT . '/utils/autoload.php');
include_once(ROOT . '/utils/stat.php');

// ----------------------------------------------------------------

$data_file = ROOT ."/tmp/".PARSER_NAME.'.json';
if(!file_exists($data_file)){
    
    //get root sections
    $url = BASE_URL . '/catalog/';
    $sections = array();
    
    $root_xpath = array(
        'item' => '//section[@class="list_products"]//div[@class="container"]/ul/li[position()>1]',
        'link' => './a/@href',
        'name' => './a/span[@class="tizer-name"]/text()'
    );
    $parser = new SectionParser($url);
    $parser->get_section_list($root_xpath, $sections);
    
    unset($parser, $url);
    
    //get list of other section
    $section_xpath = array(
        'item' => '//div[@class="products_list"]/div[@class="pr_list-item"]',
        'link' => './/a[@class="list_item-name"]/@href',
        'name' => './/a[@class="list_item-name"]/text()',
        'filter' => '//div[@id="filter_products"]'
    );
    foreach ($sections as &$section) { //section - array(node, name, code, parent_code, link)
        $url = BASE_URL . $section['link'];
        $parent_section_code = $section['code'];
        $parser = new SectionParser($url, $parent_section_code);
        get_section_type($parser, $section, $section_xpath['filter']);
        if($section['type'] == 'section')
            $parser->get_section_list($section_xpath, $sections);
        show_progress();
        //добавить рекурсию, если возможна вложенность больше 2
    }
    
    SectionParser::remove_dom_nodes($sections);
    save_json($sections, PARSER_NAME . ".json");
    print_r($sections);
    
} else $sections = json_decode(file_get_contents($data_file), true);

// ----------------------------------------------------------------
//get elements list
$elements = array();
$xpath = array(
    'id' => '//div[contains(@class,"wa_catalog-section")]/div[contains(@class,"pr_list-item")]/@id',
    'link' => './a[@class="list_item-name"]/@href',
    'name' => './a[@class="list_item-name"]/text()',
    'class_single' => './.[contains(@class, "single-product")]',
    'next_page' => '//ul[@class="pagination"]/li[@class="ax-pag-next"]/a/@href'
);
foreach ($sections as $section) {
    if ($section['type'] == 'offer') {
        $url = BASE_URL. $section['link'];
        $parent_code = $section['code'];
        get_elements_list($url, $elements, $parent_code, $xpath);
    }
}

// ----------------------------------------------------------------

function get_section_type($parser, &$section, $filter_xpath)
{
    $hasFilter = $parser->query($filter_xpath)->length;
    if ($hasFilter) {
        $section['type'] = 'offer';
    } else $section['type'] = 'section';
}

function get_elements_list($url, &$elements,  $parent_code = '', $xpath)
//собирает товары с каждого раздела с товарами
{
    $parser = new OffersParser($url, $elements, $parent_code);
    $elements = $parser->get_elements_list($xpath);

    $nextPage = $parser->parse_single_value($xpath['next_page']);
    print($nextPage);
    if ($nextPage) {
        $nextPageLink =  BASE_URL . $nextPage;
        get_elements_list($nextPageLink, $elements, $parent_code, $xpath);
    }
}
