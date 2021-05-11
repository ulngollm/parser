<?php
$parser_name = 'home_hit_1';
$base_url = 'https://www.home-heat.ru';
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
{
    include_once(ROOT . '/utils/autoload.php');
    include_once(ROOT . '/utils/stat.php');
    include_once(ROOT. '/dev/home/home-heat-class.php');
}

HomeHeatParser::init($base_url, $parser_name);

//псевдокод
HomeHeatParser::getParentSection();//потому что у верхних разделов другой шаблон
$sections = HomeParser::getSections({recursive: true});
excludeSections();//вручную
saveSectionListToJson();

foreach($sections as $section){
    if($section['elem'] != null) //разделы с товарами, не разделы разделов
    //а как еще проверить, что у разделов нет подразделов?
    //если его `code` нет хотя бы в одном `parent_code`
        $offerList = HomeParser::getElementsList();//собираем все товары
}

foreach($offerList as $offer){
    if($offer['type'] == 1){//ходим по товарам c ТП
        HomeParser::getComplexOfferInfo($offerList);//собираем детальную инфу сложных товаров
        array_push($offerList, HomeParser::getOffersList());// и торговые предложения
    }
}
foreach($offerList as $offer){ //мб этот цикл можно объединить с предыдущим?
    if($offer['type'] != 1){
        HomeParser::getDetailOfferInfo($offer);//ходим по всем товарам и собираем инфу о них
    }
}
saveOffersListToJson(); 

//из json фор
$xml = new XMLGenerator;
$xml->addSections($sections);
$xml->addElements($elements);
$xml->save_xml();
