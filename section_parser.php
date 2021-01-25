<?php
$file = file_get_contents('https://andimart.ru/catalog/');
$domain = 'https://andimart.ru';
// file_put_contents('page.html', $file);
$html = new DOMDocument();
libxml_use_internal_errors(true);
// $html->loadHTMLFile('page.html');
$html->loadHTML($file);
$parser = new DOMXPath($html);

$sections = $parser->query("//ul[@class='nav__list']/li[position()<4]//ul[@class='nav__child-group']/li[position()>1]/a");
$section_links = array();
foreach ($sections as $sect) {
    $section = array();
    $section['name'] = trim($sect->nodeValue);
    $section['url'] = $domain . $sect->attributes->getNamedItem('href')->nodeValue;
    $section['elements'] = array();
    array_push($section_links, $section);
}
//перейти на страницу раздела
// //выбрать все элементы и запихать в массив elements()
echo memory_get_usage(true) / 1024;
foreach ($section_links as &$section) {
    $elements = array();
    $base_url = $section['url'];
    get_offers_list($base_url, $elements);
    $section['elements'] = $elements;
}
print_r($section_links);
echo memory_get_peak_usage(true) / 1024;
// $data = json_encode($section_links);
// file_put_contents('structure.json', $data);

//че делать с пагинацией
function get_offers_list(string $url, array &$list,  int $page = 1)
{
    $page_url = "$url?PAGEN_1=$page";
    print("$page_url\n");
    $file = file_get_contents($page_url);
    $elem_page = new DOMDocument();
    $elem_page->loadHTML($file);
    $xpath = new DOMXPath($elem_page);
    $offers = $xpath->query("//div[@class='catalog__title']/a/@href");
    foreach ($offers as $offer) {
        $elem = $offer->nodeValue;
        array_push($list, $elem);
    }
    if ($page == 1) {
        $total_elem_count = $xpath->query("//span[@class='count__val']");
        $total_elem_count = $total_elem_count->item(0)->nodeValue;
        print("$total_elem_count\n");
        $elem_count =  $offers->length;
        if ($total_elem_count > $elem_count) {
            $pages_count = ceil($total_elem_count / $elem_count);
            for ($i = 2; $i <= $pages_count; $i++) {
                get_offers_list($url, $list, $i);
            }
        }
    }
}


