<?php

function set_offers_name(array &$offers, string $name)
{
    foreach ($offers as &$offer) {
        $offer['name'] = $name;
    }
}
function set_common_props(array $offer, array &$model, array $xpath_props, string $xpath_img)
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
        $offers = new VariationList($offers_ajax_url, $tab_id, $model_id);
        $offers->get_offers_list_page($elements, $model_id);
        Logger::show_progress('o');
    }
}

function get_elements_list(array &$section, array &$elements, array $xpath, $page = 1)
{
    list('link'=>$url, 'code'=>$section_code) =  $section;

    $url = BASE_URL."$url?PAGEN_1=$page";
    print($url . PHP_EOL);
    $parser = new OfferListParser($url, $elements, $section_code);
    $parser->get_elements_list($xpath, 'get_offer_type');
    $section['lastPage'] = $page;
    if ($page == 1) {
        $pagesCount = $parser->parse_single_value($xpath['last_page_num']);
        $section['pagesCount'] = (int)$pagesCount;
        return $pagesCount;
    } else return null;
}
function get_offer_type(Parser $parser, DOMNode $element, array $xpath)
{
    $class_attr = $parser->parse_single_value($xpath['class'], $element); //парсим класс
    if (strpos($class_attr, 'single-product')) return OfferType::SIMPLE;
    else return OfferType::COMPLEX;
}
function get_section_type($parser, &$section, $filter_xpath)
{
    $hasFilter = $parser->query($filter_xpath)->length;
    if ($hasFilter) {
        $section['type'] = 'offer';
    } else $section['type'] = 'section';
}

function save_elements(array &$elements)
{
    static $list_page = 1;
    if (count($elements) / MAX_OFFERS_COUNT > $list_page) {
        // $elements = array_splice($elements, MAX_OFFERS_COUNT);
        $list_page++;
        print(count($elements).PHP_EOL);
        // Utils::pause(10);
    }
    Utils::save_json($elements, ELEM_FILE);
}