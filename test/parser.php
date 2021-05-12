<?php
const ROOT = "/mnt/c/Users/noknok/Documents/parser/catalog_parser";
include_once(ROOT . "/utils/autoload.php");
$url = "https://www.home-heat.ru/catalog/";
$parser = new Parser($url);

$query = '//li[@class="tizer-item"]';
$single_query = "//h1";

$result = $parser->query($query);
echo ($result instanceof DOMNodeList) ? "test 1 passed\n" : "test 1 not passed\n";

$result = $parser->parse_single_value($single_query);
echo (gettype($result) == "string") ? "test 2 passed\n" : "test 2 not passed\n";

