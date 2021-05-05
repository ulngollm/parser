<?php
class OffersParser extends SectionParser
{
    public array $elements_list;

    public function __construct(string $url, array $params, array $elements_list, string $parent_code = null)
    {
        parent::__construct($url, $params, $parent_code);
        $this->elements_list = &$elements_list; 
    }

    public function get_elements_list(string $elements_xpath = null): ?array
    {
        $xpath = $this->xpath;
        $elements_id = $this->parser->query($xpath['element_id']);
        if ($elements_id->length) {
            foreach ($elements_id as $elem_id) {
                $id = $elem_id->nodeValue;

                if ($this->elem_exist($id)) {
                    $this->add_section_link($id);
                    print('duble');
                } else {
                    $elem = $this->parser->query('./..', $elem_id)->item(0);
                    $this->add_new_element($elem, $id);
                    print('elem');

                }
            }
            return $this->elements_list;
        } else return null;
    }
    private function exist_exclude_brand($name){
       return (bool) stripos($name, 'guardo');
    }
    private function elem_exist($id)
    {
        return array_key_exists($id, $this->elements_list);
    }
    private function add_section_link($id)
    {    
        $section_list = &$this->elements_list[$id]['section'];
        if(!in_array($this->parent_code, $section_list))
            array_push($section_list, $this->parent_code);
    }
    public function add_new_element(DOMNode $elem, $id)
    {
        $link = $this->parser->query($this->xpath['link'], $elem)->item(0)->nodeValue;
        $name = $this->parser->query($this->xpath['name'], $elem)->item(0)->nodeValue;
        if($this->exist_exclude_brand($name)) return false; //не добавлять исключенный бренд
        $isSimple = $this->parser->query($this->xpath['class_single'], $elem)->length;
        $type = $isSimple ? "0" : '1'; //simple of complex
        $this->elements_list[$id] = array(
            'section' => array($this->parent_code),
            'link' => $link,
            'type' => $type
        );
    }

    public function get_offers_list($elem_id){
        $offers = $this->parser->query($this->xpath['offer_link']);
        foreach($offers as $offer){
            $this->elements_list[$elem_id]['offers'][] = $offer;
        }
    }
}