<?php
class Offer extends Parser
{

    public string $name;
    public string $category_code;
    public string $article;
    public string $brand;
    public string $description;
    public string $preview_desc;
    public string $section_path;
    public string $price;
    public array $properties;
    public array $images;
    public DOMDocument $xml;
    private array $xpath;

    public function __construct(string $url, array $xpath_params, string|array $category_code = "")
    {
        parent::__construct($url);
        $this->xpath = $xpath_params;
        $this->category_code = (gettype($category_code)=="array")? implode(';', $category_code): $category_code;
        $this->images = array();
        $this->properties = array();
        $this->section_path = "";
        $this->description = "";
        $this->preview_desc = "";
        //по наличию параметров определять, что собираем с детальной страницы
        $this->set_name();
        $this->set_price();
        $this->set_properties();
        $this->set_images();
        $this->set_description();
        $this->set_brand();
        $this->set_article();
        //динамически формировать текстовые параметры
    }
    public function set_name()
    {
        $name = $this->parser->query($this->xpath['name'])->item(0);
        $this->name = ($name) ? htmlspecialchars($name->textContent) : "";
    }
    public function set_images()
    {
        $images = $this->parser->query($this->xpath['images']);
        foreach ($images as $image) {
            array_push($this->images, $image->nodeValue);
        }
    }
    public function set_section_path()
    {
        $sections = $this->query($this->xpath['section_path']);
        foreach ($sections as $key => $section) {
            $this->section_path .= $key? "/" : "";
            $this->section_path .= $section->nodeValue;
        }
        $this->section_path = trim($this->section_path);
    }
    public function set_description()
    {
        $description = $this->parser->query($this->xpath['desc'])->item(0);
        if ($description) {
            // $description->removeChild($description->childNodes[0]);
            $nodes = $description->childNodes;
            foreach ($nodes as $child) {
                $this->description .= $child->C14N();
            }
        }
    }

    public function set_properties()
    {
        $properties =  $this->parser->query($this->xpath['props']);
        foreach ($properties as $prop) {
            $property = array();
            foreach ($prop->childNodes as $child) {
                if ($child->nodeType == "1")
                    array_push($property, htmlspecialchars(trim($child->nodeValue)));
            }
            array_push($this->properties, $property);
        }
    }
    public function set_preview()
    {
        $preview = $this->parser->query($this->xpath['preview'])->item(0);
        if ($preview) $this->preview_desc = $preview->C14N();
    }
    public function set_price()
    {
        $price = $this->parser->query($this->xpath['price'])->item(0);
        $this->price = ($price) ? trim($price->nodeValue) : "";
    }
    public function set_article()
    {
        $article = $this->parser->query($this->xpath['article'])->item(0);
        $this->article = ($article) ? trim($article->nodeValue) : "";
    }
    public function set_brand()
    {
        if (isset($this->xpath['brand'])) {
            $brand = $this->parser->query($this->xpath['brand'])->item(0);
            $this->brand = ($brand) ? trim($brand->nodeValue) : "";
        }
    }
}
