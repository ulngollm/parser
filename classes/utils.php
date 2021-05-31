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

    public static function save_json(array $arr, string $filename, bool $debug = true)
    {
        $data = json_encode($arr, JSON_UNESCAPED_UNICODE);
        $dirname = $debug ? 'tmp' : 'output';
        file_put_contents(ROOT . "/$dirname/$filename", $data);
    }
    public static function load_from_json(string $filename, bool $debug = true)
    {
        $dirname = $debug ? 'tmp' : 'output';
        $filepath = ROOT . "/$dirname/$filename";
        if (file_exists($filepath)) {
            $file = file_get_contents($filepath);
            $data = json_decode($file, true);
            return $data;
        }
        else return array();
    }
    
    public static function save_progress(array $data, string $filename = null){
        Utils::save_json($data, $filename?? PARSER_NAME."_catalog.json", true);
    }
    public static function pause(int $sec){
        print('timeout 30s...'.PHP_EOL);
        sleep($sec);
    }
}
