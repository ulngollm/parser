<?php
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
include_once(ROOT . '/utils/format.php');


// $exp_result = '<span><a>ccskrссылка</a>test</span>';
// $html = '<span class="mark" align="center" style="display:none;"><a href="html" class="hre">ccskrссылка</a>test</span>';
// $result = remove_attr($html);
// echo $result;
// echo $exp_result = $result;

echo remove_symbols('<strong>&#xD;
<p>&#xD;
        ОБРАТИТЕ ВНИМАНИЕ! ТЕРМОРЕГУЛЯТОР HUMMEL SAFEDRIVE ПОДХОДИТ ДЛЯ БОЛЬШИНСТВА КЛАПАНОВ (OVENTROP, HEIMEIER, DANFOSS, HONEYWELL, HONEYWELL MNG И ДР.).&#xD;
</p>&#xD;
<p>&#xD;
</p>&#xD;
<p>&#xD;
&ndsp;');
