<?php

//собирать детальную инфу
$xpath = array(
    'name' => '//h1[@class="header_title"]',
    'price' => '//div[@class="descr_product-price"]',
    'img' => '//ul[@id="pr_slider-hor-items"]/li[not(@id="videoBox")]//a/@href',
    'desc_exclude' => '//div[@class="product_about"]//ul[@class="table-of-contents"]',
    'desc' => '//div[@class="product_about"]/div[@class="row"]/div',
    'desc_complex' => '//div[@class="description"]/div[@class="row"]/div[position()=1]',
    'props' => array(
        'item' => '//div[@class="product_about"]//ul[@class="table-of-contents"]/li',
        'name' => './/i[@class="feature_name"]/text()',
        'tooltip' => './/span[@class="feature_tooltip-text"]',
        'value' => './/i[@class="feature_value"]'
    )
);
//получить список торговых предложений
//добавить в отдельный массив

foreach ($elements as &$element) {
    if ($element['type'] == OfferType::COMPLEX) {
        $offer = init_offer($element);
        get_offers_list($offer, $element['id'], $elements);
        $element['desc'] = $offer->get_description($xpath['desc_complex']);
    }
    Utils::save_progress($catalog);
}
Logger::total_result($sections, $elements);
unset($element);

foreach ($elements as &$element) {
    if ($element['type'] != OfferType::COMPLEX) {
        $offer = init_offer($element);
        $element['name'] = $offer->get_name($xpath['name']);
        $element['price'] = $offer->get_price($xpath['price']);
        $element['img'] = $offer->get_images($xpath['img']);
        $element['props'] = $offer->get_properties($xpath['props']);
        $element['desc'] = $offer->get_description($xpath['desc'], $xpath['desc_exclude']);
    }
    if (!DEBUG) unset_debug_props($element);
    Logger::show_progress('d');
    Utils::save_progress($catalog);
}

unset($element);

function unset_debug_props(&$elem)
{
    unset($elem['id'], $elem['type']);
}

function init_offer($element)
{
    $url = BASE_URL . $element['link'];
    $category = $element['section']?? null;
    return new Offer($url, $category);
}

function convert($filename)
{
    $xml = new XMLGenerator($filename);
    $xml->save_xml('home_hit_release');
}