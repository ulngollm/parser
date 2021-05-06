<?php
function remove_attr(string $str){
    $pattern = '/<([a-z][a-z0-9]*)([^>]*?)>/i';
    $replacer = '<$1>';
    return preg_replace($pattern,$replacer, $str);
}
function extract_href(string $html){
    $pattern = '/href="(.*?)"/i';
    preg_match($pattern, $html, $matches);
    return $matches[1];
}