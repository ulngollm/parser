<?php
include_once('detail.php');
$file = file_get_contents('https://andimart.ru/catalog/');
$domain = 'https://andimart.ru';
// file_put_contents('page.html', $file);
$html = new DOMDocument();
libxml_use_internal_errors(true);
// $html->loadHTMLFile('page.html');
$html->loadHTML($file);

// var_dump($html);
$parser = new DOMXPath($html);
// $sections = $parser->query("//ul[@class='nav__list']//a[@class='sub-nav__child-link']"); все разделы
$sections = $parser->query("//ul[@class='nav__list']/li[position()<4]//ul[@class='nav__child-group']/li[position()>1]/a");
$section_links = array();

foreach ($sections as $sect) {
    $section = array();
    $section['name'] = trim($sect->nodeValue);
    $section['url'] = $domain . $sect->attributes->getNamedItem('href')->nodeValue;
    array_push($section_links, $section);
}
//перейти на страницу раздела
//выбрать все элементы и запихать в массив elements()
foreach ($section_links as $section) {
    $elements = array();
    $base_url =
        get_offers_list($section['url'], $elements);
    $section['elements'] = $elements;
}
 //че делать с пагинацией
function get_offers_list(string $url, array &$list, bool $pagination = false, int $page = 1, int $page_count = 1)
{
    $file = ($page == 1) ? file_get_contents($url) : file_get_contents($url . "/?PAGEN_1=" . $page);
    $elem_page = new DOMDocument();
    $elem_page->loadHTML($file);
    $xpath = new DOMXPath($elem_page);

    $offers = $xpath->query("//div[@class='catalog__title']/a/@href");
    foreach ($offers as $offer) {
        $elem = $offer->nodeValue;
        array_push($list, $elem);
    }
    if ($page == 1) {
        $pages = $xpath->query('//ul[@class="pagination__list"]/li[last()]/a/span/text()');
        if ($pages->length > 1) {
            $count = $pages->item(0)->nodeValue;
            get_offers_list($url, $elements, true, $page++, $count);
        }
    }
    if ($pagination && $page <= $page_count) {
        get_offers_list($url);
    }
}
print_r($section_links);

// $xml = new DOMDocument('1.0', 'utf-8');
// $xml->formatOutput = true;
// $root = $xml->createElement('offers');
// $xml->appendChild($root);
// foreach($category_list as $url){
//     print($url);
//     $offer = new Offer($url, $xml);
//     $offer_xml = $offer->get_xml();
//     $root->appendChild($offer_xml);

    
// }

// $xml->save('offers.xml');