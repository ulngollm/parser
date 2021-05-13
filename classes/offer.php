<?php
class Offer extends Parser
{

    public $id;
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

    public function __construct(string $url, $category_code = null, $id = null)
    {
        parent::__construct($url);
        if ($category_code)
            $this->set_category($category_code);
        if($id) $this->id = $id;
    }
    public function set_category($category_code)
    {
        $this->category_code = (gettype($category_code) == "array") ?
            implode(';', $category_code)
            : $category_code;
    }
    // public function set_id(string $id = null){
    //     if(!$id) $this->id = md5($this->name);
    //     else $this->id = $id;
    // }
    public function get_name(string $xpath)
    {
        $name = $this->parser->query($xpath)->item(0);
        $this->name = ($name) ? htmlspecialchars($name->textContent) : "";
    }

    public function get_images(string $xpath)
    {
        $this->images = array();
        $images = $this->parser->query($xpath);
        foreach ($images as $image) {
            array_push($this->images, $image->nodeValue);
        }
    }

    public function get_section_path(string $xpath)
    {
        $this->section_path = "";
        $sections = $this->query($xpath);
        foreach ($sections as $key => $section) {
            $this->section_path .= $key ? "/" : "";
            $this->section_path .= $section->nodeValue;
        }
        $this->section_path = trim($this->section_path);
    }

    public function get_description(string $xpath, DOMNode $exclude_node = null)
    {
        $this->description = "";
        $description = $this->parser->query($xpath)->item(0);
        if ($description) {
            if ($exclude_node) $description->removeChild($exclude_node);
            $nodes = $description->childNodes;
            foreach ($nodes as $child) {
                $this->description .= $child->C14N();
            }
        }
    }

    public function get_properties(string $xpath)
    {
        $this->properties = array();
        $properties =  $this->parser->query($xpath);
        foreach ($properties as $prop) {
            $property = array();
            foreach ($prop->childNodes as $child) {
                if ($child->nodeType == "1")
                    array_push($property, htmlspecialchars(trim($child->nodeValue)));
            }
            array_push($this->properties, $property);
        }
    }

    public function get_preview(string $xpath)
    {
        $this->preview_desc = "";
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
/*
    общая структура offer
    (
        id,
        link, 
        type,
        name, 
        [section,
        model,
        price, 
        brand, 
        properties]
    )
*/
