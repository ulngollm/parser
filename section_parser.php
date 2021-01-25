<?php
define('DOMAIN', 'https://andimart.ru');
$file = file_get_contents('https://andimart.ru/catalog/');
// file_put_contents('page.html', $file);
$html = new DOMDocument();
libxml_use_internal_errors(true);
// $html->loadHTMLFile('page.html');
$html->loadHTML($file);
$parser = new DOMXPath($html);

$sections = $parser->query("//ul[@class='nav__list']/li[position()<4]//ul[@class='nav__child-group']/li[position()>1]/a");
$section_links = array();
foreach ($sections as $sect) {
    $section = set_section_info($sect);
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
    if ($elements) $section['elements'] = $elements;
}


$root = $parser->query('//ul[@class="nav__list"]/li[position() < 4]');
foreach ($root as $root_category) {
    $category = $parser->query('./a', $root_category)->item(0);
    $section = set_section_info($category);
    array_push($section_links, $section);

    $subsections = $parser->query('.//div[@class="nav__child"]//ul[@class="nav__child-group"]', $root_category);
    foreach ($subsections as $subsection) {
        $category = $parser->query('./li[position() = 1]/a', $subsection)->item(0);
        $section = set_section_info($category);
        array_push($section_links, $section);
    }
}

print_r($section_links);

function set_section_info($node)
{
    $section = array();
    $section['name'] = $node->nodeValue;
    $section['url'] = DOMAIN . $node->getAttribute('href');
    get_section_code($section);
    return $section;
}

echo memory_get_peak_usage(true) / 1024;
$data = json_encode($section_links);
file_put_contents('structure.json', $data);

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

function get_section_code(&$section)
{
    $path = parse_url($section['url']);
    $section_path = trim($path['path'], "/");
    $path_arr = explode("/", $section_path);
    array_shift($path_arr); //удалить catalog из пути
    $section_code = array_pop($path_arr);
    $section['code'] = md5($section_code);
    if (count($path_arr) != 0) {
        $parent_section = array_pop($path_arr);
        $section['parent_code'] = md5($parent_section);
    }
}
