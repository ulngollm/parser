<?php
function save_json(array $arr, string $filename)
{
    $data = json_encode($arr, JSON_UNESCAPED_UNICODE);
    file_put_contents(__DIR__ . "/../tmp/$filename", $data);
}
function show_progress(string $symbol = "-")
{
    echo $symbol;
}
function log_parser_start(string $sitename)
{
    file_put_contents(__DIR__ . "/../log/$sitename.log", date("Y-m-d H:i:s") . " Для скрипта запрошено " . memory_get_usage(true) / 1024 . "Кб памяти.\n", FILE_APPEND);
}
function log_parser_end(string $sitename)
{
    file_put_contents(__DIR__ . "/../log/$sitename.log", date("Y-m-d H:i:s") . " Парсинг товаров выполнен успешно\n", FILE_APPEND);
}
