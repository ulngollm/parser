<?php
include_once('./class.php');
$url = 'https://daichi-aircon.ru/nastennye-split-sistemy-daichi/everest/?items_per_page=128';
$parser = new SectionParser($url);
$elements = $parser->get_elements_list($params);
print_r($elements);