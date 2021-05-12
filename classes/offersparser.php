<?php
class OffersParser extends SectionParser
//запихала логику для единичного случая в целый класс. Класс одноразовый, для переиспользования переписать
{
    public array $elements_list;
    const OFFER_TYPE = ['simple', 'complex', 'offer'];

    public function __construct(string $url, array &$elements_list, string $parent_code = null)
    {
        parent::__construct($url, $parent_code);
        $this->elements_list = $elements_list; //оставляю, потому что обращение к elements_list есть в 5 методах. Не кайф передавать этот массив из метода в метод
    }
    public function get_elements_list(array $xpath): ?array
    {

        $elements_id = $this->query($xpath['id']);
        if ($elements_id->length) {
            foreach ($elements_id as $elem_id) {
                $bx_id = $elem_id->nodeValue;
                $bx_id = (explode('_', $bx_id));
                $id = end($bx_id);
                print_r($id);
                
                if ($this->elem_exist($id)) {
                    $this->add_section_link($id);
                    print('duble');
                } else {
                    $elem = $this->query('./..', $elem_id)->item(0);
                    $this->add_new_element($elem, $id, $xpath);
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

    public function add_new_element(DOMNode $elem, $id, $xpath)
    {
        $link = $this->query($xpath['link'], $elem)->item(0)->nodeValue;
        $name = $this->query($xpath['name'], $elem)->item(0)->nodeValue;
        if($this->exist_exclude_brand($name)) return false; //не добавлять исключенный бренд
        $isSimple = $this->parse_single_value($xpath['class_single'], $elem);
        $type = $isSimple? "0" : '1'; //simple of complex
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
