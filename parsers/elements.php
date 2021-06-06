<?php
include_once(__DIR__ . '/../config.php');
include_once(__DIR__ . '/functions.php');
const PARSER_NAME = 'section';
const MAX_OFFERS_COUNT = 1000;


//get elements list
$xpath = array(
    'item' => '//div[contains(@class,"pr_list-item")]',
    'id' => './@id',
    'link' => './a[@class="list_item-name"]/@href',
    'name' => './a[@class="list_item-name"]/text()',
    'class' => './@class',
    'last_page_num' => '//ul[@class="pagination"]/li[not(@class)][last()]/a/span/text()'
);
$section_tmp_file = 'tmp/sections.json';
const ELEM_FILE = 'output/elem.json';
if (!isset($sections)) $sections = Utils::load_from_json('tmp/sections.json') ?? Utils::load_from_json('output/category.json') ?? die('The section list is empty');
$elements = Utils::load_from_json('output/elem.json')?? array();

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
        }
    }
    unset($sections[$key]);
    Utils::save_json($sections, 'tmp/sections.json');
}

function save_elements(array &$elements)
{
    static $list_page = 1;
    if (count($elements) / MAX_OFFERS_COUNT > $list_page) {
        // $elements = array_splice($elements, MAX_OFFERS_COUNT);
        $list_page++;
        Utils::pause(30);
    }
    Utils::save_json($elements, ELEM_FILE);
}

unset($xpath);


