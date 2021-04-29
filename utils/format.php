<?php
function remove_attr(string $str){
    $pattern = '/<([a-z][a-z0-9]*)([^>]*?)>/gi';
    $replacer = '<$1>';
    return preg_replace($pattern,$replacer, $str);
}