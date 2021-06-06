<?php
class OfferListParser extends SectionParser
{
    const SECTION_LINKS_FILENAME = 'tmp/elem_sect_link.json';
    public static array $section_link_list;
    public array $elements;

    public function __construct(string $url, array &$elements, ?string $section_code = null)
    {
        parent::__construct($url, $section_code);
        $this->elements = &$elements;
        self::$section_link_list = Utils::load_from_json(self::SECTION_LINKS_FILENAME) ?? array();
    }
    public function get_elements_list(array $xpath, ?callable $get_type = null)
    {
        // print_r(self::$section_link_list);
        $elements = $this->query($xpath['item']);
        foreach ($elements as $element) {
            $offer = $this->get_one_element_data($element, $xpath, $get_type);
            if($offer){
                $this->add_offer($offer);
                print_r($offer['id'] . PHP_EOL); //@debug 
            }
        }
    }
    private function get_one_element_data(DOMNode $element, array $xpath, ?callable $get_type = null) : ?array
    {
        $id = $this->parse_single_value($xpath['id'], $element);
        self::prepare_id($id);
        $name = $this->parse_single_value($xpath['name'], $element);
        $link = $this->parse_single_value($xpath['link'], $element);
        $type = $get_type ? $get_type($this, $element, $xpath) : null;

        if (!self::exist_exclude_brand($name))
            return array(
                'id' => $id,
                'type' => $type,
                'name' => $name,
                'section' => $this->section_code,
                'link' => $link
            );
        else return null;
    }

    public function add_offer($element)
    {
        $id = $element['id'];
        if (!$this->elem_exist($id)) {
            $this->add_new_elem($this->elements, $element);
        } else {
            $this->add_section_link($id);
        }
    }

    public static function prepare_id(&$id)
    {
        $tmp_id = explode('_', $id);
        $id = end($tmp_id);
    }

    public function elem_exist($id)
    {
        return array_key_exists($id, self::$section_link_list);
    }
    //todo: метод только для этого парсера. Потом переделать
    public static function exist_exclude_brand($name)
    {
        return (bool) stripos($name, 'guardo');
    }

    private function add_section_link($id)
    {
        $new_section = $this->section_code;
        $exist_section_links = &self::$section_link_list[$id];
        if (!$exist_section_links) {
            $exist_section_links = $new_section;
        } else {
            if (gettype($exist_section_links) != 'array') {
                $section = $exist_section_links;
                $exist_section_links = array($section, $new_section);
            } elseif (!in_array($new_section, $exist_section_links))
                array_push($exist_section_links, $new_section);
        }
        Utils::save_json(self::$section_link_list, self::SECTION_LINKS_FILENAME);
    }

    public static function add_new_elem(&$arr, $element)
    {
        
        $id = $element['id'];
        $arr[$id] = $element;
        if(isset($element['section'])) self::$section_link_list[$id] = $element['section'];
        Utils::save_json(self::$section_link_list, self::SECTION_LINKS_FILENAME);
    }
}
