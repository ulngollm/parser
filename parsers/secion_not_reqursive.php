<?php

if ($section['complete_page']) {
    $url = $section['link'] . "?PAGEN={$section['complete_page']}";
} else $url = $section['link'];




foreach ($sections as $section) {
    //если в разделе нет указания на последнюю страницу, значит этот раздел точно не парсили
    //не может быть такого, что lastPage пустой и раздел парсили. Все пройденные разделы из массива удаляем

    if (isset($section['lastPage']) && isset($section['countPages'])) {
        $pages_count = $section['countPages'];
        $page = $section['lastPage'] + 1;
        for ($i = $page; $i < $pages_count; $i++) {
            get_elements_list($url, $elements, $code, $xpath, $page);
        }
    } //парсим раздел с первой страницы
    else {
        $pages_count = get_elements_list($url, $elements, $code, $xpath);
        if ($pages_count) {
            $page = 2; 
            for ($i = $page; $i < $pages_count; $i++) {
                get_elements_list($url, $elements, $code, $xpath, $page);
            }
        }
    }
}
