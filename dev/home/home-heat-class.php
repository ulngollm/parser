<?php
class HomeHeatParser
{
    public static string $base_url;
    public static string $parser_name;

    public static array $sections = array();
    public static array $elements = array();

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
    public static array $elem_section_params = array(
        'element_id' => '//div[contains(@class,"wa_catalog-section")]/div[contains(@class,"pr_list-item")]/@id',
        'link' => './a[@class="list_item-name"]/@href',
        'name' => './a[@class="list_item-name"]/text()',
        'class_single' => './.[contains(@class, "single-product")]',
        'next_page' => '//ul[@class="pagination"]/li[@class="ax-pag-next"]/a/@href'
    );
    public static array $complex_offer_params = array(
        'base_offers_url'=>"/local/templates/main/components/bitrix/catalog/.default/dvs/catalog.element/.default/ajax.php?itemId=%d",
        'desc'=>'//div[@class="description"]/div[@class="row"]/div[position()=1]',
        'name'=>'//h1[@class="header_title"]'
    );
    //класс single-product у товаров без предложений
    public static array $detail_elem_param = array(
        'name' => '//h1[@class="header_title"]',
        'price' => '//div[@class="descr_product-price"]',
        'img' => '//ul[@id="pr_slider-hor-items"]/li[not(@id="videoBox")]//a/@href',
        'props' => '//div[@class="product_about"]//ul[@class="table-of-contents"]/li',
        'props_container' => '//div[@class="product_about"]//ul[@class="table-of-contents"]',
        'desc' => '//div[@class="product_about"]/div[@class="row"]/div'
    );

    public static function init($base_url, $parser_name)
    {
        self::$base_url =  $base_url;
        self::$parser_name = $parser_name;
    }

    public static function getParentSection(): ?array
    {
        $url = self::$base_url."/catalog/";
        $root_section_parser = new SectionParser($url, self::$parent_section_params);
        $sections = $root_section_parser->get_section_list();
        unset($root_section_parser);
        self::add_sections(...$sections);
        return $sections;
    }
    public static function getOfferList($url, &$elements,  $parent_code = '') 
    //собирает товары с каждого раздела с товарами
    {

        $parser = new OffersParser($url, self::$elem_section_params, $elements, $parent_code);
        $elements = $parser->get_elements_list();

        $nextPage = $parser->query(self::$elem_section_params['next_page']);
        if ($nextPage->length) {
            $nextPageLink =  self::$base_url . $nextPage->item(0)->value;
            self::getOfferList($nextPageLink, $elements, $parent_code);
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
    //только для товаров с торговыми предложениями
    public static function get_complex_offer_data($offer_data, $id){
        $params = self::$complex_offer_params;

        $offer = new ComplexOffer($offer_data['link'], $offer_data['section'], $id);

        $offer->get_name($params['name']);
        $offer->get_description($params['desc']);
        $offer->get_offers_list(self::$base_url.$params['base_offers_url']);
        return $offer->offers;
        // self::add_offers(...$offer->offers);
    }
    public static function add_offers(...$elems){
        array_push(self::$elements, ...$elems);
    }
    public static function add_sections(...$elems){
        array_push(self::$sections, ...$elems);
    }

    // только для торговых предложений или простых товаров - тип 1 или 3
    //model - Товар торгового предложения
    public static function get_detail_offer_data($offer_data, $id)
    {
        $params = self::$detail_elem_param;

        $offer = new Offer($offer_data['link'], $id);

        if($offer_data['type'] == 0){ //если простой товар
            $offer->set_category($offer_data['section']);
        } else $offer->model = $offer_data['model']; //если торговое предложение
        
        $offer->get_name($params['name']);
        $offer->get_price($params['price']);
        $offer->get_properties($params['props']);
        $offer->get_images($params['img']);
        $props_node = $offer->query($params['props_container'])->item(0);
        $offer->get_description($params['desc'], $props_node);
        $offer->description = remove_empty_tags(remove_symbols(remove_attr($offer->description)));
        return $offer;
    }
}
