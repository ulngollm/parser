<?php
include_once('classes/class.php');
$sections = array(
    'section' => '//div[@id="dropdown_5106"]/ul/li[position()>1 and position() <10]/a[@href]',
    'link' => './@href',
    'text' => './div/text()'
);
$subsection = array(
    'section' => '//li[@class="ty-subcategories__item"]',
    'parent' => '//span[@class="ty-breadcrumbs__current"]/bdi/text()',
    'link' =>  './a/@href',
    'text'=> './a/span/text()',
    'elements'=> '//div[@class="ty-product-list__item-name"]/bdi/a/@href'
);
$url = 'https://daichi-aircon.ru/nastennye-split-sistemy-daichi/everest/?items_per_page=128';
$parser = new SectionParser($url, $sections);
$sections = $parser->get_section_list();
// $elements = $parser->get_elements_list($query);\
// print_r($sections);
echo $parser->elements_xpath;