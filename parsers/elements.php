<?php
include_once(__DIR__.'/../config.php');

const MAX_OFFERS_COUNT = 1000;
$page = 1;
$sections = Utils::load_from_json(FILENAME, DEBUG);

$elements = array();
$offers = array(); //только тп
$catalog = array(
    'category' => &$sections,
    'offers' => &$elements
);
//get elements list
$xpath = array(
    'item' => '//div[contains(@class,"pr_list-item")]',
    'id' => './@id',
    'link' => './a[@class="list_item-name"]/@href',
    'name' => './a[@class="list_item-name"]/text()',
    'class' => './@class',
    'next_page' => '//ul[@class="pagination"]/li[@class="ax-pag-next"]/a/@href'
);
$page = 1;
foreach ($sections as $key => &$section) {
    if (isset($section['type']) &&  $section['type'] == 'offer') {
        $url = BASE_URL . $section['link'];
        get_elements_list($url, $elements, $section['code'], $xpath);
        Logger::show_progress('s');
        $section['complete'] = true;
        Utils::save_progress($sections, "category.json");
        // Utils::save_json($sections, "category.json", false);
        // if (DEBUG) break; //@debug
    }
}
unset($section, $xpath);


function get_elements_list(string $url, array &$elements, string $section_code, array $xpath)
{
    global $page;

    $parser = new OffersParser($url, $elements, $section_code);
    $parser->get_elements_list($xpath, 'get_offer_type');
    $nextPage = $parser->parse_single_value($xpath['next_page']);
    if ($nextPage) {
        $nextPageLink =  BASE_URL . $nextPage;
        get_elements_list($nextPageLink, $elements, $section_code, $xpath);
        Logger::show_progress('*');
    }
    if (count($elements) > MAX_OFFERS_COUNT) {
        $offers = array_splice($elements, 0, MAX_OFFERS_COUNT);
        Utils::save_json($offers, "catalog_".$page.".json", false);
        $page++;
        unset($offers);
        Utils::pause(30);
    }
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

function get_offer_type(Parser $parser, DOMNode $element, array $xpath)
{
    $class_attr = $parser->parse_single_value($xpath['class'], $element); //парсим класс
    if (strpos($class_attr, 'single-product')) return OfferType::SIMPLE;
    else return OfferType::COMPLEX;
}

$total_count = $page * MAX_OFFERS_COUNT + count($elements);
print_r('Complete. Total elem count is '. $total_count);
//todo:: generate xml


// register_shutdown_function('total_result', $sections, $elements, $offers_only);
// register_shutdown_function('save', $catalog);
// register_shutdown_function('convert', PARSER_NAME . '_catalog.json');

function save($catalog)
{
    // Utils::save_progress($catalog);
    echo 'Скрипт завершился нормас';
}
