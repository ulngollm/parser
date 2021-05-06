<?php
class ComplexOffer extends Offer{
    public array $offers;
    public int $id;

    public function __construct(string $url, array $params, $section_code, int $id)
    {
        parent::__construct($url, $params, $section_code);
        $this->offers = [];
        $this->id = $id;   
    }
    public static function get_offers_url(string $base_link, int $id){
        return sprintf($base_link, $id);
    }
    public static function get_offers_data(string $url) : array
    {
        $json = file_get_contents($url);
        return json_decode($json, true);
    }
    public function get_offers_list(string $base_link){
        $url = self::get_offers_url($base_link, $this->id);
        $offers_data = self::get_offers_data($url);
        foreach($offers_data as $offer){
            $link = extract_href($offer['name']);
            array_push($this->offers, $link);
        }
    }
}