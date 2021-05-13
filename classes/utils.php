<?php
class Utils
{
    public static function extract_href(string $html)
    {
        $pattern = '/href="(.*?)"/i';
        preg_match($pattern, $html, $matches);
        return $matches[1];
    }

    public static function remove_empty_tags(string $html)
    {
        $pattern = '/<[^\/>][^>]*>\s*<\/[^>]+>/i';
        $replacer = '';
        return preg_replace($pattern, $replacer, $html);
    }

    public static function remove_symbols(string $html)
    {
        $pattern = '/&.*;/i';
        $replacer = '';
        return preg_replace($pattern, $replacer, $html);
    }
    public static function remove_attr(string $html)
    {
        $pattern = '/<([a-z][a-z0-9]*)([^>]*?)>/i';
        $replacer = '<$1>';
        return preg_replace($pattern, $replacer, $html);
    }
    public static function clear_html(string $html)
    {
        $html = self::remove_attr($html);
        $html = self::remove_symbols($html);
        $html = self::remove_empty_tags($html);
        return $html;
    }

    public static function save_json(array $arr, string $filename)
    {
        $data = json_encode($arr, JSON_UNESCAPED_UNICODE);
        file_put_contents(ROOT . "/tmp/$filename", $data);
    }

    public static function show_progress(string $symbol = "-")
    {
        echo $symbol;
    }

    public static function log_parser_start(string $sitename)
    {
        file_put_contents(ROOT . "/log/$sitename.log", date("Y-m-d H:i:s") . " Для скрипта запрошено " . memory_get_usage(true) / 1024 . "Кб памяти.\n", FILE_APPEND);
    }
    
    public static function log_parser_end(string $sitename)
    {
        file_put_contents(ROOT . "/log/$sitename.log", date("Y-m-d H:i:s") . " Парсинг товаров выполнен успешно\n", FILE_APPEND);
    }
}
