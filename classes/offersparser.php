<?php
class OffersParser extends SectionParser
//запихала логику для единичного случая в целый класс. Класс одноразовый, для переиспользования переписать
{
    public array $elements;
    const OFFER_TYPE = ['simple', 'complex', 'offer'];

    public function __construct(string $url, array &$elements, string $section_code = null)
    {
        parent::__construct($url, $section_code);
        $this->elements = &$elements;
    }
    public function get_elements_list(array $xpath, callable $callback = null)
    {
        $elements = $this->query($xpath['item']);
        foreach ($elements as $element) {
            $offer = $this->get_one_element_data($element, $xpath, $callback);
            $this->add_offer($offer);
        }
    }
    private function get_one_element_data(DOMNode $element, array $xpath, $callback = null)
    {
        $id = $this->parse_single_value($xpath['id'], $element);
        self::prepare_id($id);
        $name = $this->parse_single_value($xpath['name'], $element);
        $link = $this->parse_single_value($xpath['link'], $element);
        
        //todo: опредеить, что товар сложный
        $type = null;
        $element_data = array(
            'id' => $id,
            'type' => $type,
            'name' => $name,
            'section' => $this->section_code,
            'link' => $link
        );
        return $element_data;
    }
    public static function prepare_id(&$id){
        $tmp_id = explode('_', $id);
        $id = end($tmp_id);
    }

    public function add_offer($element)
    {
        $id = $element['id'];
        $name = $element['name'];
        if ($this->elem_exist($id)) $this->add_section_link($id);
        elseif (!self::exist_exclude_brand($name)) $this->elements[$id] = $element;
    }

    public function elem_exist($id)
    {
        return array_key_exists($id, $this->elements);
    }
    public static function exist_exclude_brand($name, $brand = null)
    {
        return (bool) stripos($name, 'guardo');
    }

    private function add_section_link($id)
    {
        $section_links = &$this->elements[$id]['section'];
        if (!in_array($this->section_code, $section_links))
            array_push($section_links, $this->section_code);
    }

}
