<?php
class XMLGenerator
{
    public DOMDocument $xml;
    private DOMElement $categories;
    private DOMElement $offers;

    public function __construct(?string $filename = null)
    {
        $this->xml = new DOMDocument('1.0', 'utf-8');
        $this->xml->formatOutput = true;

        $root = $this->add_elem('catalog', null, $this->xml);
        $this->categories = $this->add_elem('catalog', null, $root);
        $this->offers = $this->add_elem('offers', null, $root);
        if ($filename) $this->convert_from_json($filename);
    }

    private function add_elem(string $node_name, ?string $value, DOMNode $parent_node)
    {
        $value = htmlspecialchars($value);
        $elem = $this->xml->createElement($node_name, $value);
        $parent_node->appendChild($elem);
        return $elem;
    }

    private function add_attr(string $attr_name, string $value, DOMNode $node)
    {
        $attr = $this->xml->createAttribute($attr_name);
        $attr->value = $value;
        $node->appendChild($attr);
    }

    private function add_html(string $node_name, ?string $html, DOMNode $parent_node)
    {
        if($html){
            $elem = $this->xml->createElement($node_name);
            $content = $this->xml->createCDATASection($html);
            $elem->appendChild($content);
            $parent_node->appendChild($elem);
        }
    }

    public function convert_from_json(string $filename)
    {
        $catalog = Utils::load_from_json($filename, false);
        foreach ($catalog['category'] as $category)
            $this->add_category($category);
        foreach ($catalog['offers'] as $offer)
            $this->add_offer_from_array($offer);
    }

    public function add_category(array $section)
    {
        $category = $this->add_elem('category', $section['name'], $this->categories);
        $this->add_attr('id', $section['code'], $category);
        if (isset($section['parent_code'])) {
            $this->add_attr('parentId', $section['parent_code'], $category);
        }
    }
    public function add_offer_from_array(array $offer_data)
    {
        $offer = $this->create_offer();
        foreach ($offer_data as $param => $data) {
            switch ($param) {
                case 'id':
                case 'model':
                case 'type':
                    $this->add_attr($param, $data, $offer);
                    break;
                case 'desc':
                case 'preview':
                    $this->add_html($param, $data, $offer);
                    break;
                case 'section':
                    $this->add_category_prop($data, $offer);
                    break;
                case 'img':
                case 'props':
                    $method_name = "add_$param";
                    $this->$method_name($data, $offer);
                    break;
                default:
                    $this->add_elem($param, $data, $offer);
                    break;
            }
        }
    }

    private function create_offer()
    {
        $offer = $this->xml->createElement('offer');
        $this->offers->appendChild($offer);
        return $offer;
    }

    private function add_category_prop($sections, DOMNode &$offer)
    {
        
        $value = (gettype($sections) == "array")?
        implode(';', $sections) : $sections;
        $this->add_elem('category', $value, $offer);
    }

    private function add_img(array $images, DOMNode $offer)
    {
        $img = $this->add_elem('images', null, $offer);
        foreach ($images as $image) {
            $this->add_elem('image', $image, $img);
        }
    }

    private function add_props(?array $props, DOMNode &$offer)
    {
        if($props){
            $properties = $this->add_elem('props', null, $offer);
            foreach ($props as $id => $property) {
                $this->add_single_property($property, $id, $properties);
            }
        }
    }

    private function add_single_property(array $property, string $id,  DOMNode &$properties)
    {
        $prop = $this->add_elem('property', $property['value'], $properties);
        $this->add_attr('id', $id, $prop);
        $this->add_attr('name', $property['name'], $prop);
    }

    public function save_xml(string $filename)
    {
        $this->xml->save(ROOT . "/output/$filename.xml");
    }
}
