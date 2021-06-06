<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/functions.php');


$xpath = array(
    'name' => '//h1[@class="header_title"]',
    'price' => '//div[@class="descr_product-price"]',
    'img' => '//ul[@id="pr_slider-hor-items"]/li[not(@id="videoBox")]//a/@href',
    'desc_exclude' => '//div[@class="product_about"]//ul[@class="table-of-contents"]',
    'desc' => '//div[@class="product_about"]/div[@class="row"]/div',
    'complex_desc' => '//div[@class="description"]/div[@class="row"]/div[position()=1]',
    'props' => array(
        'item' => '//div[@class="product_about"]//ul[@class="table-of-contents"]/li',
        'name' => './/i[@class="feature_name"]/text()',
        'tooltip' => './/span[@class="feature_tooltip-text"]',
        'value' => './/i[@class="feature_value"]'
    )
);
const MAX_OFFERS_COUNT = 1000;
const ELEM_FILE = 'output/offers.json';
$elem_tmp_file = 'tmp/elem.json';

$elements_queue = Utils::load_from_json($elem_tmp_file) ?? Utils::load_from_json('output/elem.json') ?? die('The element list is empty');
$offers = Utils::load_from_json(ELEM_FILE) ?? array();
OfferListParser::$section_link_list = Utils::load_from_json(OfferListParser::SECTION_LINKS_FILENAME) ?? array();

$elem_detail_file = 'output/detail.json';
$parsed_elements = Utils::load_from_json($elem_detail_file) ?? array();

foreach ($elements_queue as $key => &$element) {
    if ($element['type'] == OfferType::COMPLEX) {
        Logger::show_progress('x');
        $model = init_offer($element);
        $model_offers = array();
        $element['desc'] = $model->get_description($xpath['complex_desc']);
        save_complete_detail($element, $parsed_elements);

        get_offers_list($model, $element['id'], $model_offers);
        print(count($model_offers) . PHP_EOL);
        array_push($offers, ...$model_offers);
        save_elements($offers);
        
    } else {
        $offer = init_offer($element);
        Logger::show_progress('d');
        $element['name'] = $offer->get_name($xpath['name']);
        $element['price'] = $offer->get_price($xpath['price']);
        $element['img'] = $offer->get_images($xpath['img']);
        $element['props'] = $offer->get_properties($xpath['props']);
        $element['desc'] = $offer->get_description($xpath['desc'], $xpath['desc_exclude']);
        save_complete_detail($element, $parsed_elements);
        
    }
    unset($elements_queue[$key]);
    Utils::save_json($elements_queue, $elem_tmp_file);
}
print("Total offer count is ".count($offers));
function save_complete_detail(array $element, array &$list_complete)
{
    global $elem_detail_file;
    $id = $element['id'];
    $list_complete[$id] = $element; 
    Utils::save_json($list_complete, $elem_detail_file);
}
