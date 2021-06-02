<?php
include_once(__DIR__.'/../config.php');

$sections = [];
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
    Utils::save_progress($sections);
    // if(DEBUG) break;//@debug
}
unset($section);

SectionParser::remove_dom_nodes($sections);
Utils::save_json($sections, "category.json");



