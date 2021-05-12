<?php
class SectionParser extends Parser
{
    public ?string $section_code;

    public function __construct(string $url, ?string $section_code = null)
    {
        parent::__construct($url);
        $this->section_code = $section_code;
    }

    public function get_section_list(array $xpath, array &$section_list, ?DOMNode $parent_node = null)
    {
        $sections = $this->query($xpath['item'], $parent_node);
        foreach ($sections as $section) {
            $section_item = $this->get_one_section_data($section, $xpath);
            self::add_sections($section_list, $section_item);
        }
    }
    
    private function get_one_section_data(DOMNode $section, array $xpath){
        $link =$this->parse_single_value($xpath['link'], $section);
        $name = $this->parse_single_value($xpath['name'], $section);
        $code = md5($name . $this->section_code);
        //добаить картинку?
        $section_data = array(
            'node' => $section,
            'name' => $name,
            'code' => $code,
            'link' => $link,
            'parent_code' => $this->section_code
        );
        return $section_data;
    }

    public static function add_sections(array &$arr, ...$sections){
        array_push($arr, ...$sections);
    }
    
    public static function remove_dom_nodes(array &$arr){
        foreach($arr as &$section){
            unset($section['node']);
        }
    }

    public function get_elements_links(string $xpath, array &$element_links)
    {
        $links = $this->query($xpath);
        if ($links) {
            foreach ($links as $link) {
                $href = $link->nodeValue;
                self::add_offers($element_links, $href);
            }
        }
    }

    public static function add_offers(array &$arr, ...$elems){
        array_push($arr, ...$elems);
    }
}
