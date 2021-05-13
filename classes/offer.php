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
        $name = $this->parse_single_value($xpath);
        return ($name)? htmlspecialchars($name) : "";
    }

    public function get_images(string $xpath)
    {
        $this->images = array();
        $images = $this->parser->query($xpath);
        foreach ($images as $image) {
            array_push($this->images, $image->nodeValue);
        }
        return $this->images;
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
        return $this->section_path;
    }

    public function get_description(string $xpath, string $exclude_xpath = null)
    {
        $this->description = "";
        $description = $this->query($xpath)->item(0);
        if($exclude_xpath) $exclude_node = $this->query($exclude_xpath)->item(0);
        if ($description) {
            if ($exclude_xpath) $description->removeChild($exclude_node);
            $nodes = $description->childNodes;
            foreach ($nodes as $child) {
                $this->description .= $child->C14N();
            }
        }
        $this->description = Utils::clear_html($this->description);
        return $this->description;
    }

    public function get_properties(string $xpath)
    {
        $this->properties = array();
        $properties =  $this->query($xpath);
        foreach ($properties as $prop) {
            $property = array();
            foreach ($prop->childNodes as $child) {
                if ($child->nodeType == "1")
                    array_push($property, htmlspecialchars(trim($child->nodeValue)));
            }
            array_push($this->properties, $property);
        }
        return $this->properties;
    }

    public function get_preview(string $xpath)
    {
        $this->preview_desc = "";
        $preview = $this->query($xpath)->item(0);
        if ($preview) $this->preview_desc = $preview->C14N();
        return $this->preview_desc;
    }

    public function get_price(string $xpath)
    {
        $price = $this->query($xpath)->item(0);
        $this->price = ($price) ? trim($price->nodeValue) : "";
        return $this->price;
    }

    public function get_article(string $xpath)
    {
        $article = $this->query($xpath)->item(0);
        $this->article = ($article) ? trim($article->nodeValue) : "";
        return $this->article;
    }

    public function get_brand(string $xpath)
    {
        $brand = $this->query($xpath)->item(0);
        $this->brand = ($brand) ? trim($brand->nodeValue) : "";
        return $this->brand;
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
