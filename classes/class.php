<?php
class Parser
{
    public DOMXPath $parser;

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
    public function get_sections(array $params)
    {
        $section_list = array();
        $sections = $this->parser->query($params['section']);
        if ($params['parent']) {
            $parent_name = trim($this->parser->query($params['parent'])->item(0)->nodeValue);
            $parent_code = md5($parent_name);
        }
        foreach ($sections as $section) {
            $name = $section;
            $link = $this->parser->query($params['link'], $section)->item(0)->nodeValue;
            $name = trim($this->parser->query($params['text'], $section)->item(0)->nodeValue);
            $code = md5($name);
            $section_item = array(
                'name' => $name,
                'code' => $code,
                'link' => $link,
                'parent_code' => $parent_code ? $parent_code : null
            );
            array_push($section_list, $section_item);
        }
        return $section_list;
    }

    public function get_elements_list(string $query)
    {
        $elements = $this->parser->query($query);
        if ($elements) {
            $elements_list = array();
            foreach ($elements as $element) {
                $link = $element->nodeValue;
                array_push($elements_list, $link);
            }
            return $elements_list;
        }
    }
}