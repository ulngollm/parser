<?php
class Parser
{
    public DOMXPath $parser;

    public function __construct(string $html)
    {
        libxml_use_internal_errors(true);

        $doc = new DOMDocument();
        $doc->loadHTML($html);
        $this->parser = new DOMXPath($doc);
    }

    public static function fromUrl($url){
        $page = Downloader::get_page($url);
        $parser =  new Parser($page);
        return $parser;
    }
    
    public function query(string $query, DOMNode $contextNode = null)
    {
        return $this->parser->query($query, $contextNode);
    }

    public function parse_single_value(string $query, DOMNode $contextNode = null): ?string
    {

        $result = $this->query($query, $contextNode)->item(0);
        if (is_object($result)) return trim($result->nodeValue);
        else return null;
    }
}
