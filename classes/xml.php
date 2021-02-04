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
        $offer = $this->xml->createElement('offer');
        $name = $this->xml->createElement('name', $this->name);
        $price = $this->xml->createElement('price', $this->price);
        $code = $this->xml->createElement('code', $this->article);
        $description = $this->xml->createElement('desc', $this->description);
        $brand = $this->xml->createElement('brand', $this->brand);
        $images = $this->xml->createElement('images');
        $properties = $this->xml->createElement('props');
        $category = $this->xml->createElement('categoryId', $this->category_code);

        $this->offers->appendChild($offer);
        $offer->appendChild($name);
        $offer->appendChild($category);
        $offer->appendChild($code);
        $offer->appendChild($price);
        $offer->appendChild($description);
        $offer->appendChild($brand);
        $offer->appendChild($images);
        $offer->appendChild($properties);

        foreach ($offer->images as $image) {
            $img = $this->xml->createElement('image', $image);
            $images->appendChild($img);
        }
        foreach ($offer->properties as $property) {
            $name = $property[0];
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
}
