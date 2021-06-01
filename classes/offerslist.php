<?php
class OffersList{
    public ?array $offer_data;
    public function __construct(string $base_link, ...$params)
    {   
        $this->offer_data = $this->get_offers_list_data($base_link, ...$params);
    }
    
    public function get_offers_list_data(string $base_link, ...$params)
    {
        $url = self::get_offers_url($base_link, ...$params);
        print($url.PHP_EOL);//@debug
        $json = file_get_contents($url);
        return json_decode($json, true);
    }

    public static function get_offers_url(string $base_link, ...$params){
        return sprintf($base_link, ...$params);
    }

    public function get_offers_list_page(array &$offers, $model_id){
        if($this->offer_data)
            foreach($this->offer_data as $offer){
                $offer_data = $this->get_one_offer_data($offer, $model_id);
                OffersParser::add_new_elem($offers, $offer_data);
            }
        else return null;
    }

    public function get_one_offer_data(array $offer, $model_id){
        $link = Utils::extract_href($offer['name']);
        unset($offer['name'], $offer['canBuy']);

        foreach($offer['props'] as $prop_code=>&$prop_value){
            $prop_value = array(
                'code'=>$prop_code,
                'value'=>$prop_value
            );
        }
        $offer['type'] = OfferType::OFFER;
        $offer['model'] = $model_id;
        $offer['link']= $link;
        return $offer;
    }
    
}