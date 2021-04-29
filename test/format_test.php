<?php
include_once(__DIR__ . '/../utils/format.php');

$exp_result = '<span><a>ccskrссылка</a>test</span>';
$html = '<span class="mark" align="center" style="display:none;"><a href="html" class="hre">ccskrссылка</a>test</span>';
$result = remove_attr($html);
echo $result;
echo $exp_result = $result;
