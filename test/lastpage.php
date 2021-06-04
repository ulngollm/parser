<?php

$section = array(
    // 'lastPage' => 2,
    // 'countPages'=>15
);

$url = 'home-heat.ru';
$lastPage = $section['lastPage'] ?? 1;
echo $lastPage . PHP_EOL;
$pages_count = $section['countPages'] ?? test($url);
var_dump($pages_count);
if ($pages_count) {
    $page = $lastPage + 1;
    for ($i = $page; $i <= $pages_count; $i++) {   
        test($url, $i);
    }
}
unset($section);

function test($url, $page = 1){
    $url = "$url?PAGEN=$page";
    echo $url . PHP_EOL;
    if ($page == 1) {
        $pagesCount = rand(1, 18);
        return $pagesCount;
    } else return null;
}
// $section = array(
//     'lastPage' => 2,
//     'countPages'=>15,
//     'link'=>'asdasd'
// );
// list('link'=>$url, 'lastPage'=>$page, 'countPages'=>$pages_count) = $section;
// print_r($url);
// print_r($pages_count);
// print_r($page);