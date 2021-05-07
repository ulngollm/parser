<?php
include_once(Config::ROOT . '/utils/autoload.php');

$url = "https://www.home-heat.ru/catalog/alyuminievye-radiatory/";
$parser = new Parser($url);
$section = $parser->query('//div[@class="products_list"]/div[@class="pr_list-item"]');
print_r($section);
$filter = $parser->query('//div[@id="filter_products"]');
print_r($filter);
