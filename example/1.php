<?php

include_once(__DIR__ . '/../config.php');
// const CACHE_ENABLE = false;

$url = 'https://donstu.ru/';

$xpath = array(
    'name' => '//div[@class="rector-name"]/div[@class="link_rector"]',
    'post' => '//div[@class="rector-post"]',
); 

$parser = Parser::fromUrl($url);

foreach ($xpath as $key => $path){
    $value = $parser->parse_single_value($path);
    print($value . PHP_EOL);
}

