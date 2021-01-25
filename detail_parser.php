<?php
//см https://программисту.рф/theory/xpath-example/
//https://onedev.net/post/458
//https://soltau.ru/index.php/themes/dev/item/413
// $file = file_get_contents('https://krasnodar.andimart.ru/catalog/otoplenie/kotelnaya_komnata/aksessuary_dlya_kotlov/turbonasadka_comfort_l_140_d_140_x_80_mm_35_40_kvt/');

function get_detail_info($url)
{
    // $page = file_get_contents($url);

    $html = new DOMDocument();
    libxml_use_internal_errors(true);
    $html->loadHTMLFile('detail.html');
    // $html->loadHTML($page);

    $parser = new DOMXPath($html);
    $name = $parser->query("//h1/text()");
    $name = $name[0]->textContent;

    $images = $parser->query("//div[@class='product-card__thumb-in']/img/@src");
    $images_list = array();
    foreach ($images as $img) {
        array_push($images_list, $img->nodeValue);
    }
    print_r($images_list);

    $description = $parser->query("//div[@id='product-descr']//p/text()");
    $description = $description[0]->nodeValue; //выдает только первый абзац текста

    // todo: как собирать пару свойство-значение в xml
    $properties =  $parser->query("//table[@class='props']//tr[@class='props__item']");
    $property_list = array();
    foreach ($properties as $prop) {
        $property = array();
        foreach ($prop->childNodes as $child) {
            if ($child->nodeType == "1")
                array_push($property, trim($child->nodeValue));
        }
        array_push($property_list, $property);
    }

    $price = $parser->query("//div[@class='product-card__price']/text()[3]");
    $price = $price[0]->nodeValue;

    $article = $parser->query('//div[@class="product-card__prop"][1]/text()[2]');
    $article = $article[0]->nodeValue;

    $brand = $parser->query('//div[@class="product-card__prop"][2]/text()[2]');
    $brand = $brand[0]->nodeValue;
}
// $documents = $parser->query('//div[@class="b-list__item"]//li[@class="check-list__item"]/a');
// $docs_list = array();
// foreach($documents as $document){
//     print_r($document);
//     // $doc = array(
//     // array_push($docs_list, $document->nodeValue);
// }
// print_r($docs_list);



include_once('xml_generator.php');
echo memory_get_peak_usage(true) / 1024;
