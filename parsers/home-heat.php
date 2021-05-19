<?php
const PARSER_NAME = 'home_hit_debug';
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
const BASE_URL = 'https://www.home-heat.ru';
const DEBUG = true;

include_once(ROOT . '/autoload.php');

// ----------------------------------------------------------------
$data_file = "category.json";
$sections = Utils::load_from_json($data_file, false);
$elements = array();
$offers = array(); //только тп
$catalog = array(
    'category' => &$sections,
    'offers' => &$elements
);

if (!$sections) {
    //get root sections
    $url = BASE_URL . '/catalog/';
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
    //section - array(node, name, code, section_code, link)
    foreach ($sections as &$section) {
        $url = BASE_URL . $section['link'];
        $parent_section_code = $section['code'];
        $parser = new SectionParser($url, $parent_section_code);
        get_section_type($parser, $section, $section_xpath['filter']);
        if ($section['type'] == 'section')
            $parser->get_section_list($section_xpath, $sections);
        Logger::show_progress();
        Utils::save_progress($catalog);
        // if(DEBUG) break;//@debug
    }
    unset($section);

    SectionParser::remove_dom_nodes($sections);
    Utils::save_json($sections, "category.json");
}

// ----------------------------------------------------------------
//get elements list
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
        Logger::show_progress('e');
        Utils::save_progress($catalog);
        if (DEBUG) break; //@debug
    }
}
unset($section, $xpath);

// ----------------------------------------------------------------

//собирать детальную инфу
$xpath = array(
    'name' => '//h1[@class="header_title"]',
    'price' => '//div[@class="descr_product-price"]',
    'img' => '//ul[@id="pr_slider-hor-items"]/li[not(@id="videoBox")]//a/@href',
    'desc_exclude' => '//div[@class="product_about"]//ul[@class="table-of-contents"]',
    'desc' => '//div[@class="product_about"]/div[@class="row"]/div',
    'desc_complex' => '//div[@class="description"]/div[@class="row"]/div[position()=1]',
    'props' => array(
        'item' => '//div[@class="product_about"]//ul[@class="table-of-contents"]/li',
        'name' => './/i[@class="feature_name"]/text()',
        'tooltip' => './/span[@class="feature_tooltip-text"]',
        'value' => './/i[@class="feature_value"]'
    )
);
//получить список торговых предложений
//добавить в отдельный массив

foreach ($elements as &$element) {
    $url = BASE_URL . $element['link'];
    $category = $element['section'] ?? null;
    $offer = new Offer($url, $category);
    $element['name'] = $offer->get_name($xpath['name']);
    if ($element['type'] == OfferType::COMPLEX) {
        $element['desc'] = $offer->get_description($xpath['desc_complex']);
        get_offers_list($offer, $element['id'], $offers);
        
    } elseif ($element['type'] == OfferType::SIMPLE) {
        $element['price'] = $offer->get_price($xpath['price']);
        $element['img'] = $offer->get_images($xpath['img']);
        $element['props'] = $offer->get_properties($xpath['props']);
        $element['desc'] = $offer->get_description($xpath['desc'], $xpath['desc_exclude']);
    }
    if (!DEBUG) unset_debug_props($element);
    if (DEBUG) print_r($element); //@debug
    Logger::show_progress('d');
    Utils::save_progress($catalog);
}
unset($element);

foreach ($offers as $element) {
    $url = BASE_URL . $element['link'];
    $offer = new Offer($url);
    $element['name'] = $offer->get_name($xpath['name']);
    $element['price'] = $offer->get_price($xpath['price']);
    $element['img'] = $offer->get_images($xpath['img']);
    $element['props'] = $offer->get_properties($xpath['props']);
    $element['desc'] = $offer->get_description($xpath['desc'], $xpath['desc_exclude']);
    Utils::save_progress($offers, "_offers");
}


function get_section_type($parser, &$section, $filter_xpath)
{
    $hasFilter = $parser->query($filter_xpath)->length;
    if ($hasFilter) {
        $section['type'] = 'offer';
    } else $section['type'] = 'section';
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

function get_offer_type(Parser $parser, DOMNode $element, array $xpath)
{
    $class_attr = $parser->parse_single_value($xpath['class'], $element); //парсим класс
    if (strpos($class_attr, 'single-product')) return OfferType::SIMPLE;
    else return OfferType::COMPLEX;
}
function get_offers_list(Offer $parser, int $model_id, array &$elements)
{
    $xpath = '//ul[@class="nav nav-tabs"]//li[last()]//@data-key';
    $max_tab_id = $parser->parse_single_value($xpath);

    $offers_ajax_url = BASE_URL . '/local/templates/main/components/bitrix/catalog/.default/dvs/catalog.element/.default/ajax.php?tabId=%d&itemId=%d';
    for ($tab_id = 0; $tab_id <= $max_tab_id; $tab_id++) {
        $offers = new OffersList($offers_ajax_url, $tab_id, $model_id);
        $offers->get_offers_list($elements, $model_id);
        Logger::show_progress('o');
    }
}
function unset_debug_props(&$elem)
{
    unset($elem['id'], $elem['type']);
}
//todo:: generate xml


register_shutdown_function('save', $catalog);
function save($catalog)
{
    Utils::save_progress($catalog);
    echo 'Скрипт завершился нормас';
}
