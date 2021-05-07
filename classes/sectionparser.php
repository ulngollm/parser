<?php
class SectionParser extends Parser
{
    public array $xpath;
    public string $parent_code;
    public array $sections;

    public function __construct(string $url, array $params, ?string $parent_code = null)
    {
        parent::__construct($url);
        if ($parent_code) $this->parent_code = $parent_code;
        $this->xpath = $params;
    }
    //порядок аргументов можно забыть и запутаться
    public function get_section_list(array $params = null, array $parent_section = null): ?array
    {
        if (!$params) $params = $this->xpath;

        $parent_node = $parent_section ? $parent_section['node'] : null;
        $parent_code = $parent_section ? $parent_section['code'] : $this->parent_code;

        //трудно вспомнить, че ему надо

        $section_list = array();
        $sections = $this->parser->query($params['section'], $parent_node);
        foreach ($sections as $section) {
            $link = $this->parser->query($params['link'], $section)->item(0)->nodeValue;
            $name = trim($this->parser->query($params['name'], $section)->item(0)->nodeValue);
            $code = md5($name . $this->parent_code);
            //добаить картинку?
            $section_item = array(
                'node' => $section,
                'name' => $name,
                'code' => $code,
                'link' => $link,
                'parent_code' => $parent_code
            );
            array_push($section_list, $section_item);
        }
        return $section_list;
    }

    public function get_elements_list(string $elements_xpath = null): ?array
    {
        if (!$elements_xpath) $elements_xpath = $this->xpath['elements'];

        $elements = $this->parser->query($elements_xpath);
        if ($elements) {
            $elements_list = array();
            foreach ($elements as $element) {
                $link = $element->nodeValue;
                array_push($elements_list, $link);
            }
            return $elements_list;
        } else return null;
    }
}
