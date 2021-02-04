<?php
class Offer
{

    public string $name;
    public string $category_code;
    public string $article;
    public string $brand;
    public string $description;
    public array $properties;
    public string $price;
    public array $images;
    public DOMDocument $xml;

    private DOMDocument $html;
    private DOMXPath $parser;

    public function __construct(string $url, string $category_code, DOMDocument $xml)
    {
        $this->html = new DOMDocument();
        libxml_use_internal_errors(true);

        try {
            $id = curl_init($url);
            curl_setopt($id, CURLOPT_RETURNTRANSFER, 1);
            $page = curl_exec($id);
            curl_close($id);
            $this->html->loadHTML($page);
            $this->parser = new DOMXPath($this->html);
        } catch (Exception $e) {}
        $this->category_code = $category_code;
        $this->images = array();
        $this->properties = array();
        $this->xml = $xml;

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
        $name = $this->parser->query("//h1/text()")->item(0);
        $this->name = ($name)? $name->textContent : "";
    }
    public function set_images()
    {
        $images = $this->parser->query("//div[@class='product-card__image']/a/@href");
        foreach ($images as $image) {
            array_push($this->images, DOMAIN . $image->nodeValue);
        }
    }
    public function set_description()
    {
        $description = $this->parser->query("//div[@id='product-descr']//p")->item(0);
        $this->description = ($description) ? trim($description->nodeValue) : "";
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
        $price = $this->parser->query("//div[@class='product-card__price']/text()[3]")->item(0);
        $this->price = ($price) ? trim($price->nodeValue) : "";
    }
    public function set_article()
    {
        $article = $this->parser->query('//div[@class="product-card__prop"][1]/text()[2]')->item(0);
        $this->article = ($article)? trim($article->nodeValue):"";
    }
    public function set_brand()
    {
        $brand = $this->parser->query('//div[@class="product-card__prop"][2]/text()[2]')->item(0);
        $this->brand = ($brand)? trim($brand->nodeValue):"";
    }
    public function get_xml()
    {
        $root = $this->xml->createElement('offer');
        $name = $this->xml->createElement('name', $this->name);
        $price = $this->xml->createElement('price', $this->price);
        $code = $this->xml->createElement('code', $this->article);
        $description = $this->xml->createElement('desc', $this->description);
        $brand = $this->xml->createElement('brand', $this->brand);
        $images = $this->xml->createElement('images');
        $properties = $this->xml->createElement('props');
        $category = $this->xml->createElement('categoryId', $this->category_code);

        $this->xml->appendChild($root);
        $root->appendChild($name);
        $root->appendChild($category);
        $root->appendChild($code);
        $root->appendChild($price);
        $root->appendChild($description);
        $root->appendChild($brand);
        $root->appendChild($images);
        $root->appendChild($properties);

        foreach ($this->images as $image) {
            $img = $this->xml->createElement('image', $image);
            $images->appendChild($img);
        }
        foreach ($this->properties as $property) {
            $name = $property[0];
            $value = $property[1];
            $code = md5($name);
            $prop = $this->xml->createElement('prop');
            $propName = $this->xml->createElement('name', $name);
            $propCode = $this->xml->createAttribute('id');
            $propCode->value =$code;
            $propValue = $this->xml->createElement('value', $value);
            $prop->appendChild($propName);
            $prop->appendChild($propCode);
            $prop->appendChild($propValue);
            $properties->appendChild($prop);
        }
        return $root;
    }
}
