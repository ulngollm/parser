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

    public function __construct(string $url, array $xpath_params, $category_code = "")
    {
        parent::__construct($url);
        // $this->xpath = $xpath_params;
        $this->category_code = (gettype($category_code) == "array") ? implode(';', $category_code) : $category_code;
        $this->images = array();
        $this->properties = array();
        $this->section_path = "";// а это для чего инициализируется? для xml generator?
        $this->description = "";
        $this->preview_desc = "";
        //по наличию параметров определять, что собираем с детальной страницы
        //тогда должно быть четкое соглашение об именовании параметров
        
        // а не лучше ли ручками вызывать те методы, которые нужны?
        // $this->get_name($xpath_params['name']);
        // $this->get_price($xpath_params['price']);
        // $this->get_properties($xpath_params['props']);
        // $this->get_images($xpath_params['images']);
        // $this->get_description($xpath_params['desc']);
        // if($this->xpath['brand']) $this->get_brand($xpath_params['brand']);
        // $this->get_article($xpath_params['article']);
    }
    public function get_name(string $xpath)
    {
        $name = $this->parser->query($xpath)->item(0);
        $this->name = ($name) ? htmlspecialchars($name->textContent) : "";
    }

    public function get_images(string $xpath)
    {
        $images = $this->parser->query($xpath);
        foreach ($images as $image) {
            array_push($this->images, $image->nodeValue);
        }
    }

    public function get_section_path(string $xpath)
    {
        $sections = $this->query($xpath);
        foreach ($sections as $key => $section) {
            $this->section_path .= $key ? "/" : "";
            $this->section_path .= $section->nodeValue;
        }
        $this->section_path = trim($this->section_path);
    }

    public function get_description(string $xpath)
    {
        $description = $this->parser->query($xpath)->item(0);
        if ($description) {
            // $description->removeChild($description->childNodes[0]);
            $nodes = $description->childNodes;
            foreach ($nodes as $child) {
                $this->description .= $child->C14N();
            }
        }
    }

    public function get_properties(string $xpath)
    {
        $properties =  $this->parser->query($xpath);
        foreach ($properties as $prop) {
            $property = array();
            foreach ($prop->childNodes as $child) {
                if ($child->nodeType == "1")
                    array_push($property, htmlspecialchars(trim($child->nodeValue)));
            }
            array_push($this->properties, $property);
            //$this->properties[] = $property;//почему не так?
        }
    }

    public function get_preview(string $xpath)
    {
        $preview = $this->parser->query($xpath)->item(0);
        if ($preview) $this->preview_desc = $preview->C14N();
    }

    public function get_price(string $xpath)
    {
        $price = $this->parser->query($xpath)->item(0);
        $this->price = ($price) ? trim($price->nodeValue) : "";
    }

    public function get_article(string $xpath)
    {
        $article = $this->parser->query($xpath)->item(0);
        $this->article = ($article) ? trim($article->nodeValue) : "";
    }

    public function get_brand(string $xpath)
    {
            $brand = $this->parser->query($xpath)->item(0);
            $this->brand = ($brand) ? trim($brand->nodeValue) : "";
    }
}
