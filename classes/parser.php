<?php
class Parser
{
    public DOMXPath $parser;
    private string $hashname;

    public function __construct($url)
    {
        $this->hashname = ROOT . "/cache/" . md5($url) . ".html";
        $html = new DOMDocument();
        libxml_use_internal_errors(true);
        $page = $this->get_page($url);
        $html->loadHTML($page);
        $this->parser = new DOMXPath($html);
    }

    public function __destruct()
    {
        if (file_exists($this->hashname)) {
            echo $this->hashname;
            unlink($this->hashname);
        }
        // die();
    }

    private function get_page($url)
    {
        $page_cache = $this->load_from_cache($url);
        if ($page_cache) {
            $page = $page_cache;
            echo 'cache';
        } else {
            $id = curl_init($url);
            curl_setopt($id, CURLOPT_RETURNTRANSFER, 1);
            $page = curl_exec($id);
            curl_close($id);
            file_put_contents($this->hashname, $page);
        }
        return $page;
    }
    public function load_from_cache()
    {
        $filename = $this->hashname;
        if (file_exists($filename)) {
            return file_get_contents($filename);
        } else return null;
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
