<?php
// $page = file_get_contents('https://andimart.ru/catalog/');
$html = new DOMDocument();
libxml_use_internal_errors(true);
$html->loadHTMLFile('page.html');
$parser = new DOMXPath($html);

// $item = $parser->query('//ul[@class="pagination__list"]/li[last()]/a/span/text()');
// print_r($item->item(0)->nodeValue);
// $nav = $parser->query("//ul[@class='nav__list']/li[position()<4]//ul[@class='nav__child-group']");
// $sections = array();
// foreach ($nav as $section) {
//     $parent_section = array();
//     $parent_section['sections'] =  array();
//     // print_r($section);
//     $headline = $parser->query("./li[position()=1]/a", $section);
//     print_r($headline->item(0)->nodeValue);
//     $parent_section['name'] = trim($headline->item(0)->nodeValue);
//     $elements = $parser->query("./li[position()>1]/a", $section);
//     foreach ($elements as $element) {
//         $subsection = array();
//         $subsection['name'] = trim($sect->nodeValue);
//         $subsection['url'] = $domain . $sect->attributes->getNamedItem('href')->nodeValue;
//         $subsection['elements'] = array();
//         array_push($parent_section['sections'], $subsection);
//     }
//     array_push($sections, $parent_section);
// }
define('DOMAIN','https://andimart.ru');
$section_links = array();
$root = $parser->query('//ul[@class="nav__list"]/li[position() < 4]');
foreach($root as $root_category){
    $category = $parser->query('./a', $root_category)->item(0);
    $section = set_section_info($category);
    array_push($section_links, $section);

    $subsections = $parser->query('.//div[@class="nav__child"]//ul[@class="nav__child-group"]', $root_category);
    foreach($subsections as $subsection){
        $category = $parser->query('./li[position() = 1]/a', $subsection)->item(0);
        $section = set_section_info($category);
        array_push($section_links, $section);
    }
}
function set_section_info($node){
    $section = array();
    $section['name'] = trim($node->nodeValue);
    $section['url'] = DOMAIN.$node->getAttribute('href');
    get_section_code($section);
    print_r($section);
    return $section;
}
function get_section_code(&$section){
    $path = parse_url($section['url']);
    $section_path = trim($path['path'], "/");
    $path_arr = explode("/", $section_path);
    $section_code = array_pop($path_arr);
    $section['code'] = md5($section_code);
    $parent_section = array_pop($path_arr);
    $section['parent_code'] = md5($parent_section);  
}
print_r($section_links);