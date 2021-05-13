<?php
class OfferType {
    const SIMPLE = 0;
    const COMPLEX = 1;
    const OFFER = 2;
}
class OffersParser extends SectionParser
{
    public array $elements;
    public function __construct(string $url, array &$elements, string $section_code = null)
    {
        parent::__construct($url, $section_code);
        $this->elements = &$elements;
    }
    public function get_elements_list(array $xpath, callable $get_type = null)
    {
        $elements = $this->query($xpath['item']);
        foreach ($elements as $element) {
            $offer = $this->get_one_element_data($element, $xpath, $get_type);
            $this->add_offer($offer);
        }
    }
    private function get_one_element_data(DOMNode $element, array $xpath, callable $get_type = null)
    {
        $id = $this->parse_single_value($xpath['id'], $element);
        self::prepare_id($id);
        $name = $this->parse_single_value($xpath['name'], $element);
        $link = $this->parse_single_value($xpath['link'], $element);        
        $type = $get_type($this, $element, $xpath['class']);

        $element_data = array(
            'id' => $id,
            'type' => $type,
            'name' => $name,
            'section' => $this->section_code,
            'link' => $link
        );
        return $element_data;
    }

    public function add_offer($element)
    {
        $id = $element['id'];
        $name = $element['name'];
        if ($this->elem_exist($id)) $this->add_section_link($id);
        elseif (!self::exist_exclude_brand($name)) $this->add_new_elem($element);
    }

    public static function prepare_id(&$id)
    {
        $tmp_id = explode('_', $id);
        $id = end($tmp_id);
    }

    public function elem_exist($id)
    {
        return array_key_exists($id, $this->elements);
    }
    //метод только для этого парсера. Потом переделать
    public static function exist_exclude_brand($name)
    {
        return (bool) stripos($name, 'guardo');
    }

    private function add_section_link($id)
    {
        $section_links = &$this->elements[$id]['section'];
        if (!in_array($this->section_code, $section_links))
            array_push($section_links, $this->section_code);
    }

    private function add_new_elem($element){
        $id = $element['id'];
        $this->elements[$id] = $element;
    }
}
