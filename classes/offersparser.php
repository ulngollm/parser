<?php
class OffersParser extends Parser
{
    public array $xpath;
    public $parent_code;
    public array $all_elements;

    public function __construct(string $url, array $params, string $parent_code)
    {
        parent::__construct($url);
        $this->parent_code = $parent_code;
        $this->xpath = $params;
    }
    public function get_elements()
    {
        if
    }
    public function elem_($id){
        return !$this->all_elements[$id];
    }
    public function addSectionLink($id, $section_code)
    {
        array_push($this->all_elements[$id]['section'], $section_code);
    }
}
