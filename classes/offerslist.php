<?php
class OffersList{
    public ?array $offer_data;
    public function __construct(string $base_link, int $id, int $tab_id)
    {   
        $this->offer_data = $this->get_offers_list_data($base_link, $tab_id,  $id);
    }
    
    public function get_offers_list_data(string $base_link, $tab_id, $id)
    {
        $url = self::get_offers_url($base_link, $tab_id, $id);
        $json = file_get_contents($url);
        return json_decode($json, true);
    }

    public static function get_offers_url(string $base_link, int $tab_id, int $id){
        return sprintf($base_link, $tab_id, $id);
    }

    public function get_offers_list(array &$offers, $model_id){
        if($this->offer_data)
            foreach($this->offer_data as $offer){
                $offer_data = $this->get_one_offer_data($offer, $model_id);
                OffersParser::add_new_elem($offers, $offer_data);
            }
        else return null;
    }

    public function get_one_offer_data(array $offer, $model_id){
        $id = $offer['id'];
        $link = Utils::extract_href($offer['name']);
        return array(
            'id'=>$id,
            'type'=>OfferType::OFFER,
            'model'=>$model_id,
            'link'=>$link
        );
    }
    
}