<?php
class Offer extends Parser
{

    public string $name;
    public string $category_code;
    public string $article;
    // public string $brand;
    public string $description;
    public array $properties;
    public string $price;
    public array $images;
    public DOMDocument $xml;
    private array $xpath;

    public function __construct(string $url, string $category_code, array $xpath_params)
    {
        parent::__construct($url);
        $this->xpath = $xpath_params;
        $this->category_code = $category_code;
        $this->images = array();
        $this->properties = array();

        $this->set_name();
        $this->set_price();
        $this->set_properties();
        $this->set_images();
        $this->set_description();
        // $this->set_brand();
        $this->set_article();
    }
    public function set_name()
    {
        $name = $this->parser->query($this->xpath['name'])->item(0);
        $this->name = ($name)? $name->textContent : "";
    }
    public function set_images()
    {
        $images = $this->parser->query($this->xpath['images']);
        foreach ($images as $image) {
            array_push($this->images, $image->nodeValue);
        }
    }
    public function set_description()
    {
        $description = $this->parser->query($this->xpath['desc'])->item(0);
        $this->description = ($description) ? trim($description->nodeValue) : "";
    }
    public function set_properties()
    {
        $properties =  $this->parser->query($this->xpath['props']);
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
        $price = $this->parser->query($this->xpath['price'])->item(0);
        $this->price = ($price) ? trim($price->nodeValue) : "";
    }
    public function set_article()
    {
        $article = $this->parser->query($this->xpath['article'])->item(0);
        $this->article = ($article)? trim($article->nodeValue):"";
    }
    public function set_brand()
    {
        $brand = $this->parser->query($this->xpath['brand'])->item(0);
        $this->brand = ($brand)? trim($brand->nodeValue):"";
    }
}
