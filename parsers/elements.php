<?php
include_once(__DIR__.'/../config.php');


// $sections = Utils::load_from_json(FILENAME, DEBUG);
$url = 'https://www.home-heat.ru/catalog/trubchatye-radiatory-s-bokovym-podklyucheniem/';

$elements = array();
$offers = array();
$catalog = array(
    // 'category' => &$sections,
    'model' => &$elements,
    'offer' => &$offers
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
// $page = 1;

get_elements_list($url, $elements, '', $xpath);
Logger::show_progress('s');
unset($xpath);
print(count($elements) . PHP_EOL);


include_once(__DIR__.'/detail.php');

function get_elements_list(string $url, array &$elements, string $section_code, array $xpath)
{
    global $catalog;
    if(count($elements)> 10) return;
    print(count($elements));
    $parser = new OffersParser($url, $elements, $section_code);
    $parser->get_elements_list($xpath, 'get_offer_type');
    $nextPage = $parser->parse_single_value($xpath['next_page']);
    Utils::save_progress($catalog);
    if ($nextPage) {
        // return;
        unset($parser);
        Logger::show_progress('->');
        $nextPageLink =  BASE_URL . $nextPage;
        get_elements_list($nextPageLink, $elements, $section_code, $xpath);

    } else Logger::show_progress('*');
}


function get_offer_type(Parser $parser, DOMNode $element, array $xpath)
{
    $class_attr = $parser->parse_single_value($xpath['class'], $element); //парсим класс
    if (strpos($class_attr, 'single-product')) return OfferType::SIMPLE;
    else return OfferType::COMPLEX;
}

// $total_count = count($offers) + count($elements);
print_r("Complete. Total elem count is $count\n");
printf("Total offer count is %d\n", count($offers));
printf("Total elements count is %d\n", count($elements));

//todo:: generate xml


// register_shutdown_function('total_result', $sections, $elements, $offers_only);
register_shutdown_function('save', $catalog);
// register_shutdown_function('convert', PARSER_NAME . '_catalog.json');

function save($catalog)
{
    Utils::save_progress($catalog);
    echo 'Скрипт завершился нормас';
}
