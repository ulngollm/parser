<?php
class Logger
{
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
    public static function total_result($sections, $elements)
    {
        $offers_only = array_filter($elements, function ($value) {
            return $value['type'] == OfferType::OFFER;
        });
        print('Всего разделов ' . count($sections) . PHP_EOL);
        print('Всего товаров ' . count($elements) . PHP_EOL);
        print('Из них ' . count($offers_only) . " торговых предложений\n");
    }
}
