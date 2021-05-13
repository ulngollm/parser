<?php
const PARSER_NAME = 'home_hit_2';
const ROOT = '/home/ully/Документы/parser/catalog_parser';
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
    print_r(count($sections));

    foreach ($sections as &$section) { //section - array(node, name, code, section_code, link)
        $url = BASE_URL . $section['link'];
        $parent_section_code = $section['code'];
        $parser = new SectionParser($url, $parent_section_code);
        get_section_type($parser, $section, $section_xpath['filter']);
        if($section['type'] == 'section')
            $parser->get_section_list($section_xpath, $sections);
            //массив увеличивается после доьавления, цикл foreach удлинняется
        show_progress();
        // print_r(count($sections));
        break;
    }
    unset($section);
    
    SectionParser::remove_dom_nodes($sections);
    save_json($sections, PARSER_NAME . ".json");
    print_r($sections);
    
} else $sections = json_decode(file_get_contents($data_file), true);

function get_section_type($parser, &$section, $filter_xpath)
{
    $hasFilter = $parser->query($filter_xpath)->length;
    if ($hasFilter) {
        $section['type'] = 'offer';
    } else $section['type'] = 'section';
}

// ----------------------------------------------------------------
//get elements list
$elements = array();
$xpath = array(
    'item'=>'//div[contains(@class,"pr_list-item")]',
    'id' => './@id',
    'link' => './a[@class="list_item-name"]/@href',
    'name' => './a[@class="list_item-name"]/text()',
    'class' => './@class',
    'next_page' => '//ul[@class="pagination"]/li[@class="ax-pag-next"]/a/@href'
);
foreach ($sections as $section) {
    if (isset($section['type']) &&  $section['type'] == 'offer') {
        $url = BASE_URL. $section['link'];
        $section_code = $section['code'];
        get_elements_list($url, $elements, $section_code, $xpath);
    }
}

function get_elements_list(string $url, array &$elements, string $section_code, array $xpath)
{
    $parser = new OffersParser($url, $elements, $section_code);
    $parser->get_elements_list($xpath, 'get_offer_type');
    $nextPage = $parser->parse_single_value($xpath['next_page']);
    if ($nextPage) {
        $nextPageLink =  BASE_URL . $nextPage;
        get_elements_list($nextPageLink, $elements, $section_code, $xpath);
    }
}

function get_offer_type(Parser $parser, DOMNode $element, array $xpath){
    $class_attr = $parser->parse_single_value($xpath['class'], $element);//парсим класс
    if(strpos($class_attr, 'single-product')) return OfferType::SIMPLE;
    else return OfferType::COMPLEX;
}
// ----------------------------------------------------------------
$offers_ajax_url = BASE_URL.'/local/templates/main/components/bitrix/catalog/.default/dvs/catalog.element/.default/ajax.php?itemId=%d';
$xpath = array(
    'desc'=>'//div[@class="description"]/div[@class="row"]/div[position()=1]',
    'name'=>'//h1[@class="header_title"]'
);
foreach($elements as $id=>$offer){
    if($offer['type'] == OfferType::COMPLEX){
        $url = BASE_URL.$offer['link'];
        $section_code = $offer['section'];
        $model = new ComplexOffer($url, $section_code, $id);
        $offer->get_name($xpath['name']);
        $offer->get_description($xpath['desc']);
        $offer->get_offers_list($offers_ajax_url, $elements);//доьавлять полноценные эдементы, как в OffersParser
    }
    else {
        //get detail info
    }
}


register_shutdown_function('save',$sections, $elements);
function save($sections, $elements){
    print_r($sections);
    print_r($elements);
    echo 'Скрипт завершился нормас';
}

