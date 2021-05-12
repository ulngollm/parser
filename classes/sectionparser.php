<?php
class SectionParser extends Parser
{
    public ?string $parent_code;

    public function __construct(string $url, ?string $parent_code = null)
    {
        parent::__construct($url);
        $this->parent_code = $parent_code;
    }

    public function get_section_list(array $xpath, array &$section_list, bool $recursive = false, ?DOMNode $parent_node = null)
    {
        $sections = $this->query($xpath['item'], $parent_node);
        foreach ($sections as $section) {
            $section_item = $this->get_one_section_data($section, $xpath);
            self::add_sections($section_list, $section_item);

            if($recursive){
                $url = $this->prepare_section_link($section_item['link']);
                $parent_code = $section_item['code'];
                $subsection_parser = new SectionParser($url, $parent_code);
                $subsection_parser->get_section_list($xpath, $section_list, $recursive);
                //а что делать, если рекурсивно и элементы собрать?
            }
        }
    }
    
    private function get_one_section_data($section, $xpath){
        $link =$this->parse_single_value($xpath['link'], $section);
        $name = $this->parse_single_value($xpath['name'], $section);
        $code = md5($name . $this->parent_code);
        //добаить картинку?
        $section_data = array(
            'node' => $section,
            'name' => $name,
            'code' => $code,
            'link' => $link,
            'parent_code' => $this->parent_code
        );
        return $section_data;
    }

    public static function add_sections(array &$arr, ...$sections){
        array_push($arr, ...$sections);
    }

    protected function prepare_section_link($link){
        return !self::is_relative_link($link)? $link : $this->base_url.$link;
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
