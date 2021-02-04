<?php
class Parser
{
    private DOMXPath $parser;

    function __construct($url)
    {
        $html = new DOMDocument();
        libxml_use_internal_errors(true);
        $page = $this->get_page($url);
        $html->loadHTML($page);
        $this->parser = new DOMXPath($html);
    }
    private function get_page($url)
    {
        $id = curl_init($url);
        curl_setopt($id, CURLOPT_RETURNTRANSFER, 1);
        $page = curl_exec($id);
        curl_close($id);
        return $page;
    }
    public function query(string $query, $contextNode = null)
    {
        return $this->parser->query($query, $contextNode);
    }
}


class SectionParser extends Parser
{
    public string $section_xpath;
    public string $link_xpath;
    public string $text_xpath;
    public string $elements_xpath;
    public $parent_code;

    public function __construct(string $url, array $params, string $parent_code = null)
    {
        parent::__construct("$url?items_per_page=128");
        $this->parent_code = $parent_code;
        $this->elements_xpath = "";

        foreach($params as $key=>$value){
            $prop = "{$key}_xpath";
            $this->$prop = $value;
        }
    }

    public function get_section_list()
    {
        $section_list = array();
        $sections = $this->parser->query($this->section_xpath);
        foreach ($sections as $section) {
            $link = $this->parser->query($this->link_xpath, $section)->item(0)->nodeValue;
            $name = trim($this->parser->query($this->text_xpath, $section)->item(0)->nodeValue);
            $code = md5($name);
            $section_item = array(
                'name' => $name,
                'code' => $code,
                'link' => $link,
                'parent_code' => $this->parent_code
            );
            array_push($section_list, $section_item);
        }
        return $section_list;
    }

    public function get_elements_list()
    {
        $elements = $this->parser->query($this->elements_xpath);
        if ($elements) {
            $elements_list = array();
            foreach ($elements as $element) {
                $link = $element->nodeValue;
                array_push($elements_list, $link);
            }
            return $elements_list;
        }
        else return false;
    }
}