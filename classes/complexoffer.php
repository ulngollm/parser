<?php
class ComplexOffer extends Offer{
    public array $offers;

    public function __construct(string $url, array $xpath_params, $section_code)
    {
        parent::__construct($url, $xpath_params, $section_code);
        $this->offers = [];
    }
    public function get_offers_list(string $xpath){
        $offers_link = $this->query($xpath);
        print_r($offers_link);
        foreach($offers_link as $offer){
            array_push($this->offers, $offer->nodeValue);
        }
    }
}