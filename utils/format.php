<?php
function remove_attr(string $html){
    $pattern = '/<([a-z][a-z0-9]*)([^>]*?)>/i';
    $replacer = '<$1>';
    return preg_replace($pattern,$replacer, $html);
}
function extract_href(string $html){
    $pattern = '/href="(.*?)"/i';
    preg_match($pattern, $html, $matches);
    return $matches[1];
}
function remove_symbols(string $html){
    $pattern = '/&#xD;/i';
    $replacer = '';
    return preg_replace($pattern,$replacer, $html);
}
function remove_empty_tags(string $html){
    $pattern = '/<[^\/>][^>]*>\s*<\/[^>]+>/i';
    $replacer = '';
    return preg_replace($pattern, $replacer, $html);
}