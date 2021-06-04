<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/functions.php');
const PARSER_NAME = 'section';
const MAX_OFFERS_COUNT = 1000;

// $modelSectionLinks = Utils::load_from_json('tmp/elem_sect_link.json');
if (!isset($sections)) $sections = Utils::load_from_json('tmp/sections.json') ?? Utils::load_from_json('output/category.json') ?? array();
print_r(current($sections));
$list_page = 1;
// die();
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

foreach ($sections as $key => &$section) {
    if ($section['type'] == 'offer') {
        $pages_count = $section['pagesCount'] ?? get_elements_list($section, $elements, $xpath);
        $lastPage = $section['lastPage'] ?? 1;
        if ($pages_count) {
            for ($page = $lastPage + 1; $page <= $pages_count; $page++) {
                echo $page;
                get_elements_list($section, $elements, $xpath, $page);
                save_elements($elements);
                Utils::save_json($sections, 'tmp/sections.json');
            }
            print_r($elements);
        }
    }
    unset($sections[$key]);
    Utils::save_json($sections, 'tmp/sections.json');
}

function save_elements(array &$elements)
{
    global $list_page;
    if (count($elements) > MAX_OFFERS_COUNT) {
        $elements = array_splice($elements, MAX_OFFERS_COUNT);
        $list_page++;
    }
    Utils::save_json($elements, "output/elements_{$list_page}.json");
}

Logger::show_progress('s');
unset($xpath);
print(count($elements) . PHP_EOL);


// include_once(__DIR__ . '/detail.php');






// $total_count = count($offers) + count($elements);
// // print_r("Complete. Total elem count is $count\n");
// printf("Total offer count is %d\n", count($offers));
// printf("Total elements count is %d\n", count($elements));

//todo:: generate xml


// register_shutdown_function('total_result', $sections, $elements, $offers_only);
// register_shutdown_function('save', $catalog);
// register_shutdown_function('convert', PARSER_NAME . '_catalog.json');

// function save($catalog)
// {
//     Utils::save_progress($catalog);
//     echo 'Скрипт завершился нормас';
// }
