<?php
include_once(__DIR__.'/../config.php');
include_once(__DIR__.'/functions.php');

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
    'last_page_num' => '//ul[@class="pagination"]/li[not(@class)][last()]/a/span/text()'
);
// $page = 1;

get_elements_list($url, $elements, '', $xpath);
Logger::show_progress('s');
unset($xpath);
print(count($elements) . PHP_EOL);


include_once(__DIR__.'/detail.php');






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
