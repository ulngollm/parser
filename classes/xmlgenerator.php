<?php
class XMLGenerator
{
    public DOMDocument $xml;
    private DOMElement $categories;
    private DOMElement $offers;

    public function __construct()
    {
        $this->xml = new DOMDocument('1.0', 'utf-8');
        $this->xml->formatOutput = true;
        $root = $this->xml->createElement('catalog');
        $this->xml->appendChild($root);
        $this->categories = $this->xml->createElement('categories');
        $root->appendChild($this->categories);
        $this->offers = $this->xml->createElement('offers');
        $root->appendChild($this->offers);
    }
    public function add_category(array $section)
    {
        $category = $this->xml->createElement('category', $section['name']);
        $this->categories->appendChild($category);
        $category_code = $this->xml->createAttribute('id');
        $category_code->value = $section['code'];
        $category->appendChild($category_code);
        if (isset($section['parent_code'])) {
            $parent_category_code = $this->xml->createAttribute('parentId');
            $parent_category_code->value = $section['parent_code'];
            $category->appendChild($parent_category_code);
        }
    }
    public function add_offer(Offer $offer)
    {
        $offer_elem = $this->xml->createElement('offer');
        $name = $this->xml->createElement('name', $offer->name);
        $price = $this->xml->createElement('price', $offer->price);
        $code = $this->xml->createElement('code', $offer->article);
        if ($offer->description) {
            $description = $this->xml->createElement('desc');
            $description_html = $this->xml->createCDATASection($offer->description);
            $offer_elem->appendChild($description);
            $description->appendChild($description_html);
        }
        if ($offer->preview_desc) {
            $preview = $this->xml->createElement('preview_text');
            $preview_html = $this->xml->createCDATASection($offer->preview_desc);
            $offer_elem->appendChild($preview);
            $preview->appendChild($preview_html);
        }
        $brand = $this->xml->createElement('brand', $offer->brand);
        $images = $this->xml->createElement('images');
        $properties = $this->xml->createElement('props');
        $category = $this->xml->createElement('category', $offer->category_code ? $offer->category_code : $offer->section_path);

        $this->offers->appendChild($offer_elem);
        $offer_elem->appendChild($name);
        $offer_elem->appendChild($category);
        $offer_elem->appendChild($code);
        $offer_elem->appendChild($price);
        if (isset($offer->brand))   $offer_elem->appendChild($brand);
        $offer_elem->appendChild($images);
        $offer_elem->appendChild($properties);

        foreach ($offer->images as $image) {
            $img = $this->xml->createElement('image', $image);
            $images->appendChild($img);
        }
        foreach ($offer->properties as $property) {
            $name = trim($property[0], ":");
            $value = $property[1];
            $code = md5($name);
            $prop = $this->xml->createElement('prop');
            $propName = $this->xml->createElement('name', $name);
            $propCode = $this->xml->createAttribute('id');
            $propCode->value = $code;
            $propValue = $this->xml->createElement('value', $value);
            $prop->appendChild($propName);
            $prop->appendChild($propCode);
            $prop->appendChild($propValue);
            $properties->appendChild($prop);
        }
    }
    public function save_xml(string $filename){
        $this->xml->save(__DIR__."/../output/$filename.xml");
    }
}