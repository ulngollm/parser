<?php
$page = file_get_contents('https://andimart.ru/catalog/otoplenie/vodyanoe_otoplenie/radiatory/');
$html = new DOMDocument();
libxml_use_internal_errors(true);
$html->loadHTML($page);
$parser = new DOMXPath($html);
$item = $parser->query('//ul[@class="pagination__list"]/li[last()]/a/span/text()');
print_r($item->item(0)->nodeValue);