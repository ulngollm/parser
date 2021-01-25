<?php
class Offer
{
    public string $name;
    public string $categoryId;
    public string $article;
    public string $brand;
    public string $description;
    public array $properties;
    public string $price;
    public array $images;
    public DOMDocument $xml;

    private DOMDocument $html;
    private DOMXPath $parser;

    public function __construct(string $url, int $parentId, DOMDocument $xml)
    {
        // $this->category = $parent['name'];
        // $this->category = "Отопление";

        $this->html = new DOMDocument();
        libxml_use_internal_errors(true);
        try{
            $page = file_get_contents($url);
            $this->html->loadHTML($page);
            $this->parser = new DOMXPath($this->html);
        }
        catch(Exception $e){

        }
        $this->categoryId = $parentId;
        $this->images = array();
        $this->properties = array();
        $this->xml = $xml;
        // $this->xml = new DOMDocument('1.0', 'utf-8');
        // $this->xml->formatOutput = true;

        $this->set_name();
        $this->set_price();
        $this->set_properties();
        $this->set_images();
        $this->set_description();
        $this->set_brand();
        $this->set_article();
    }
    public function set_name()
    {
        $name = $this->parser->query("//h1/text()");
        $this->name = $name[0]->textContent;
    }
    public function set_images()
    {
        $images = $this->parser->query("//div[@class='product-card__thumb-in']/img/@src");
        foreach ($images as $image) {
            array_push($this->images, $image->nodeValue);
        }
    }
    public function set_description()
    {
        $description = $this->parser->query("//div[@id='product-descr']//p");
        $this->description = trim($description[0]->nodeValue);
    }
    public function set_properties()
    {
        $properties =  $this->parser->query("//table[@class='props']//tr[@class='props__item']");
        foreach ($properties as $prop) {
            $property = array();
            foreach ($prop->childNodes as $child) {
                if ($child->nodeType == "1")
                    array_push($property, trim($child->nodeValue));
            }
            array_push($this->properties, $property);
        }
    }
    public function set_price()
    {
        $price = $this->parser->query("//div[@class='product-card__price']/text()[3]");
        $this->price = trim($price[0]->nodeValue);
    }
    public function set_article()
    {
        $article = $this->parser->query('//div[@class="product-card__prop"][1]/text()[2]');
        $this->article = trim($article[0]->nodeValue);
    }
    public function set_brand()
    {
        $brand = $this->parser->query('//div[@class="product-card__prop"][2]/text()[2]');
        $this->brand = trim($brand[0]->nodeValue);
    }
    public function get_xml(){
        $root = $this->xml->createElement('offer');
        $name = $this->xml->createElement('name', $this->name);
        $price = $this->xml->createElement('price', $this->price);
        $code = $this->xml->createElement('code', $this->article);
        $description = $this->xml->createElement('desc', $this->description);
        $brand = $this->xml->createElement('brand', $this->brand);
        $images = $this->xml->createElement('images');
        $properties = $this->xml->createElement('props');
        $category = $this->xml->createElement('categoryId', $this->categoryId);
        
        $this->xml->appendChild($root);
        $root->appendChild($name);
        $root->appendChild($category);
        $root->appendChild($code);
        $root->appendChild($price);
        $root->appendChild($description);
        $root->appendChild($brand);
        $root->appendChild($images);
        $root->appendChild($properties);

        foreach($this->images as $image){
            $img = $this->xml->createElement('image', $image);
            $images->appendChild($img);
        }
        foreach($this->properties as $property){
            $prop = $this->xml->createElement('prop');
            $propName = $this->xml->createElement('name', $property[0]);
            $propValue = $this->xml->createElement('value', $property[1]);
            $prop->appendChild($propName);
            $prop->appendChild($propValue);
            $properties->appendChild($prop);
        }
       return $root;
    }
}
