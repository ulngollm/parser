<?php
$parser_name = 'home_hit_test';
include_once(__DIR__ . '/../utils/autoload.php');
include_once(__DIR__ . '/../utils/stat.php');

$base_url = 'https://www.home-heat.ru';

// getParentSection($base_url);
// getSections2ndDepth($root_sections, $base_url, $subsection_params, $parser_name);
$sections = array();
$elements = array(
    'id' : [
        type:'single',
        sections: [

        ]
    ]
);



class HomeParser
{
    public static string $base_url;
    public static string $parser_name;
    public static array $parent_section_params = array(
        'section' => '//section[@class="list_products"]//div[@class="container"]/ul/li[position()>1]',
        'link' => './a/@href',
        'name' => './a/span[@class="tizer-name"]/text()'
    );
    public static array $subsection_params = array(
        'section' => '//div[@class="products_list"]/div[@class="pr_list-item"]',
        'link' => './/a[@class="list_item-name"]/@href',
        'name' => './/a[@class="list_item-name"]/text()',
        'filter' => '//div[@id="filter_products"]'
    );
    public static array $elem_params = array(
        'element' => '//div[contains(@class,"wa_catalog-section")]/div[contains(@class,"pr_list-item")]',
        'link'=>'./a[@class="list_item-name"]',
        'next_page' => '//ul[@class="pagination"]/li[@class="ax-pag-next"]/a/@href'
    );
    //класс single-product у товаров без предложений
    public static array $detail_elem_param = array();
    
    public static function init($base_url, $parser_name)
    {
        self::$base_url =  $base_url;
        self::$parser_name = $parser_name;
    }
    
    public static function getParentSection(): ?array
    {
        $url = "${self::$base_url}/catalog/";
        $root_section_parser = new SectionParser($url, self::$parent_section_params);
        unset($root_section_parser);
        return $root_section_parser->get_section_list();
    }
    
    public static function getOfferList($url)
    {
        
        $parser = new SectionParser($url, self::$elem_params);
        $elements = $parser->get_elements_list();
        print_r($elements);
        array_push($section_elements, ...$elements);
        $nextPage = $parser->query(self::$elem_params['next_page']);
        if($nextPage->length){
            $nextPageLink =  self::$base_url.$nextPage->item(0)->value;
            self::getOfferList($nextPageLink);
        }
        
        
    }
    
    public static function getSubsections(&$parent_section)
    {
        $url = self::$base_url . $parent_section['link'];
        $parser = new SectionParser($url, self::$subsection_params, $parent_section['code']);
        $filter = $parser->query(self::$subsection_params['filter'])->length;
        
        $isElementsPage = (bool) $filter;
        if (!$isElementsPage) {
            $subsections = $parser->get_section_list(); 
            $parent_section['elements'] = null;
            return $subsections;
        } else return null;
    }
    


    public static function getSections2ndDepth($root_sections)
    { 
        $section_list = $root_sections;
        foreach ($root_sections as $root_sect) {
            $subsections = self::getSubsections($root_sect);
            if($subsections) array_push($section_list,...$subsections);    
        }
        save_json($section_list, "${self::$parser_name}.json");
    }
    function getSubOffers()
    {

    }
}

HomeParser::init($base_url, $parser_name);
HomeParser::getOfferList('https://www.home-heat.ru/catalog/vse-vertikalnye-radiatory/');


//сначала разделы собрать в json

//элементы
//как привязывать торговые предложения