<?php
include_once(__DIR__ . '/../config.php');

$url = "https://www.home-heat.ru/catalog/trubchatye-stalnye-radiatory-arbonia/trubchatye-radiatory-arbonia-190-mm-vysotoy-belye/";
$xpath = '//div[@class="description"]/div[@class="row"]/div[position()=1]';
$parser = new Offer($url);
$desc = $parser->get_description($xpath);
print($desc);
print(PHP_EOL);
// $prepared_desc = Utils::clear_html($desc);
// print($prepared_desc);