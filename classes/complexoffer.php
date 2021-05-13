<?php
class ComplexOffer extends Offer{

    public function __construct(string $url, $section_code, int $id)
    {
        parent::__construct($url, $section_code, $id);
    }

    public function get_offers_list(string $base_link, array &$offers){
        $offers_data = $this->get_offers_list_data($base_link);

        foreach($offers_data as $offer){
            $offer_data = $this->get_one_offer_data($offer);
            SectionParser::add_offers($offers, $offer_data);//не так
        }
    }

    public static function get_offers_url(string $base_link, int $id){
        return sprintf($base_link, $id);
    }

    public function get_offers_list_data(string $base_link) : array
    {
        $url = self::get_offers_url($base_link, $this->id);
        $json = file_get_contents($url);
        return json_decode($json, true);
    }
    
    public function get_one_offer_data($offer){
        $model = $this->id;
        $id = $offer['id'];
        $link = extract_href($offer['name']);
        return array(
            'id'=>$id,
            'type'=>OfferType::OFFER,
            'model'=>$model,
            'link'=>$link
        );
    }
    
}