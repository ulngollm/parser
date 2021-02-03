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
    public function get_parent_sections()
    {
        $section_list = array();
        $sections = $this->parser->query('//div[@id="dropdown_5106"]/ul/li[position()>1 and position() <10]/a[@href]');
        foreach ($sections as $section) {
            $link = $this->parser->query('./@href', $section)->item(0)->nodeValue;
            $name = $this->parser->query('./text()', $section)->item(0)->nodeValue;
            $code = md5($name);
            $section_item = array('name' => $name, 'code' => $code, 'link' => $link);
            array_push($section_list, $section_item);
        }
        return $section_list; //массив с названиями разделов и ссылками
    }

    public function get_subsections()
    {
        //ващет мы сначала должны попасть на страницу
        $section_list = array();
        $sections = $this->parser->query('//li[@class="ty-subcategories__item"]');
        foreach ($sections as $section) {
            $name;
            $code;
            $parent_code;
        }
    }
}

// $section_parser = new Parser('https://daichi-aircon.ru/');
// $sections = $section_parser->query('//div[@id="dropdown_5106"]/ul/li[position()>1 and position() <10]/a[@href]');
// foreach($sections as $section){
//     $url = $section_parser->parser->query('./@href', $section)->item(0)->nodeValue;
//     $name = $section_parser->query('./text()', $section)->item(0)->nodeValue;
//     print("$name \t $url\n");
// }