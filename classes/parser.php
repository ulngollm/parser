<?php
class Parser
{
    public DOMXPath $parser;
    protected string $base_url;

    public function __construct($url)
    {
        $this->base_url = self::get_base_url($url);

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

    public static function get_base_url(string $url){
        $path = parse_url($url, PHP_URL_PATH);
        $base_url = str_replace($path, '', $url);
        return $base_url;   
    }

    public static function is_relative_link(string $url){
        return (bool) !parse_url($url,PHP_URL_HOST);
    }
    
    public function query(string $query, DOMNode $contextNode = null)
    {
        return $this->parser->query($query, $contextNode);
    }

    public function parse_single_value(string $query, DOMNode $contextNode = null)
    {
        return trim($this->query($query, $contextNode)->item(0)->nodeValue);
    }

}
