<?php
include_once(__DIR__ . '/../config.php');
const MAX_OFFERS_COUNT = 1000;
$page = 1;
//собирать детальную инфу
$xpath = array(
    'name' => '//h1[@class="header_title"]',
    'price' => '//div[@class="descr_product-price"]',
    'img' => '//ul[@id="pr_slider-hor-items"]/li[not(@id="videoBox")]//a/@href',
    'desc_exclude' => '//div[@class="product_about"]//ul[@class="table-of-contents"]',
    'desc' => '//div[@class="product_about"]/div[@class="row"]/div',
    'complex' => array(
        'desc' => '//div[@class="description"]/div[@class="row"]/div[position()=1]',
        'img' => '//ul[@id="content_slider-items"]/li/img/@src'
    ),
    'props' => array(
        'item' => '//div[@class="product_about"]//ul[@class="table-of-contents"]/li',
        'name' => './/i[@class="feature_name"]/text()',
        'tooltip' => './/span[@class="feature_tooltip-text"]',
        'value' => './/i[@class="feature_value"]'
    )
);
$count = 0;
$page = 1;
foreach ($elements as &$element) {
    // if((count($offers) + $count) / MAX_OFFERS_COUNT > $page) {
    //     $page++;
    //     Utils::pause(30);
    if ($element['type'] == OfferType::COMPLEX) {
        $model = init_offer($element);
        Logger::show_progress('x');
        $element['desc'] = $model->get_description($xpath['complex']['desc']);
        if(!$count) {//@debug
            $model_offers = array();
            get_offers_list($model, $element['id'], $model_offers);
            set_common_props(current($model_offers), $element, $xpath['props'], $xpath['img'] );
            set_offers_name($offers, $element['name']);
            array_push($offers, ...$model_offers);
        }
    } else {
        $offer = init_offer($element);
        Logger::show_progress('d');
        $element['name'] = $offer->get_name($xpath['name']);
        $element['price'] = $offer->get_price($xpath['price']);
        $element['img'] = $offer->get_images($xpath['img']);
        $element['props'] = $offer->get_properties($xpath['props']);
        $element['desc'] = $offer->get_description($xpath['desc'], $xpath['desc_exclude']);
    }
    // if (!DEBUG) unset_debug_props($element);
    Utils::save_progress($catalog);
    $count++;
    break;
}
unset($offer);
// print_r($offers);
foreach($offers as &$elem){
    $model_id = $elem['model'];
    $elem['name'] = $elements[$model_id]['name'];
}
function set_offers_name(array &$offers, string $name){
    foreach($offers as &$offer){
        $offer['name'] = $name;
    }
}
function set_common_props(array $offer, array &$model, array $xpath_props, string $xpath_img )
{
    $elem = init_offer($offer);
    $props = $elem->get_properties($xpath_props);
    $images = $elem->get_images($xpath_img);
    $model['img'] = $images;
    $model['props'] = $props;
    unset($elem);
}

function unset_debug_props(&$elem)
{
    unset($elem['id'], $elem['type']);
}

function init_offer(array $element)
{
    $url = BASE_URL . $element['link'];
    $category = $element['section'] ?? null;
    return new Offer($url, $category);
}

function convert($filename)
{
    $xml = new XMLGenerator($filename);
    $xml->save_xml('home_hit_release');
}

function get_offers_list(Offer $parser, int $model_id, array &$elements)
{
    $xpath = '//ul[@class="nav nav-tabs"]//li[last()]//@data-key';
    $max_tab_id = $parser->parse_single_value($xpath);

    $offers_ajax_url = BASE_URL . '/local/templates/main/components/bitrix/catalog/.default/dvs/catalog.element/.default/ajax.php?tabId=%d&itemId=%d';
    for ($tab_id = 0; $tab_id <= $max_tab_id; $tab_id++) {
        $offers = new OffersList($offers_ajax_url, $tab_id, $model_id);
        $offers->get_offers_list_page($elements, $model_id);
        Logger::show_progress('o');
    }
}
