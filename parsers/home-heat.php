<?php
const PARSER_NAME = 'home_hit_2';
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
const BASE_URL = 'https://www.home-heat.ru';

include_once(ROOT . '/autoload.php');

// ----------------------------------------------------------------

$data_file = ROOT . "/tmp/" . PARSER_NAME . '.json';
if (!file_exists($data_file)) {

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

    foreach ($sections as &$section) { //section - array(node, name, code, section_code, link)
        $url = BASE_URL . $section['link'];
        $parent_section_code = $section['code'];
        $parser = new SectionParser($url, $parent_section_code);
        get_section_type($parser, $section, $section_xpath['filter']);
        if ($section['type'] == 'section')
            $parser->get_section_list($section_xpath, $sections);
        //массив увеличивается после добавления, цикл foreach удлинняется
        Utils::show_progress();
        // print_r(count($sections));//@debug
        break;
    }
    unset($section);

    SectionParser::remove_dom_nodes($sections);
    Utils::save_json($sections, PARSER_NAME . ".json");
    print_r($sections);//@debug
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
    'item' => '//div[contains(@class,"pr_list-item")]',
    'id' => './@id',
    'link' => './a[@class="list_item-name"]/@href',
    'name' => './a[@class="list_item-name"]/text()',
    'class' => './@class',
    'next_page' => '//ul[@class="pagination"]/li[@class="ax-pag-next"]/a/@href'
);
foreach ($sections as $key => $section) {
    if (isset($section['type']) &&  $section['type'] == 'offer') {
        $url = BASE_URL . $section['link'];
        $section_code = $section['code'];
        get_elements_list($url, $elements, $section_code, $xpath);
        Utils::show_progress('e');
        break; //@debug
    }
}
unset($section, $xpath);

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

function get_offer_type(Parser $parser, DOMNode $element, array $xpath)
{
    $class_attr = $parser->parse_single_value($xpath['class'], $element); //парсим класс
    if (strpos($class_attr, 'single-product')) return OfferType::SIMPLE;
    else return OfferType::COMPLEX;
}
// ----------------------------------------------------------------

//собирать детальную инфу
$xpath = array(
    'name' => '//h1[@class="header_title"]',
    'price' => '//div[@class="descr_product-price"]',
    'img' => '//ul[@id="pr_slider-hor-items"]/li[not(@id="videoBox")]//a/@href',
    'props' => '//div[@class="product_about"]//ul[@class="table-of-contents"]/li',
    'desc_exclude' => '//div[@class="product_about"]//ul[@class="table-of-contents"]',
    'desc' => '//div[@class="product_about"]/div[@class="row"]/div',
    'desc_complex' => '//div[@class="description"]/div[@class="row"]/div[position()=1]',
);
foreach ($elements as &$element) {
    $url = BASE_URL . $element['link'];
    $category = $element['section'] ?? null;
    $offer = new Offer($url, $category);
    $element['name'] = $offer->get_name($xpath['name']);

    if ($element['type'] == OfferType::COMPLEX) {
        $element['desc'] = $offer->get_description($xpath['desc_complex']);
        get_offers_list($offer, $element['id'], $elements);
    } else {
        $element['price'] = $offer->get_price($xpath['price']);
        $element['img'] = $offer->get_images($xpath['img']);
        $element['desc'] = $offer->get_description($xpath['desc'], $xpath['desc_exclude']);
        $element['props'] = $offer->get_properties($xpath['props']);
    }
    Utils::show_progress('d');
    // print_r($element);@debug
}

function get_offers_list(Offer $parser, int $model_id, array &$elements)
{
    $xpath = '//ul[@class="nav nav-tabs"]//li[last()]//@data-key';
    $max_tab_id = $parser->parse_single_value($xpath);

    $offers_ajax_url = BASE_URL . '/local/templates/main/components/bitrix/catalog/.default/dvs/catalog.element/.default/ajax.php?tabId=%d&itemId=%d';
    for ($tab_id = 0; $tab_id <= $max_tab_id; $tab_id++){
        $offers = new OffersList($offers_ajax_url, $model_id, $tab_id);
        $offers->get_offers_list($elements, $model_id);
        Utils::show_progress('o');
    }
}
//todo:: generate xml


register_shutdown_function('save', $sections, $elements);
function save($sections, $elements)
{
    $catalog = array(
        'category' => $sections,
        'offers' => $elements
    );
    Utils::save_json($catalog, PARSER_NAME . "catalog.json");
    echo 'Скрипт завершился нормас';
}
