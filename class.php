<?php
//получить корневые разделы
// $sections = $parser->query('//div[@id="dropdown_5106"]/ul/li[position()>1 and position() <10]/a[@href]');
// foreach($sections as $section){
//     $url = $parser->query('./@href', $section);
//     file_get_contents($section);
// }


class Parser{
    public DOMXPath $parser;

    function __construct($url)
    {
        $html = new DOMDocument();
        libxml_use_internal_errors(true);
        $page = $this->get_page($url);
        $html->loadHTML($page);
        $this->parser = new DOMXPath($html);
    }
    private function get_page($url){
        $id = curl_init($url);
        curl_setopt($id, CURLOPT_RETURNTRANSFER, 1);
        $page = curl_exec($id);
        curl_close($id);
        return $page;
    }
    public function query(string $query){
        return $this->parser->query($query);
    }
    public function get_elements(){
        //получить их прямо в массив
    }
}