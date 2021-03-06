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
        $pattern = '/(&.*;)|(\n)|(\t)|(\r)/i';
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

    public static function save_json(array $arr, string $filepath)
    {
        $data = json_encode($arr, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        // $dirname = $debug ? 'tmp' : 'output';
        file_put_contents(ROOT . "/$filepath", $data);
    }
    public static function load_from_json(string $filepath, bool $debug = true): ?array
    {
        // $dirname = $debug ? 'tmp' : 'output';
        // $filepath = ROOT . "/$dirname/$filename";
        if (file_exists(ROOT."/$filepath")) {
            $file = file_get_contents(ROOT."/$filepath");
            $data = json_decode($file, true);
            return $data;
        }
        else return null;
    }
    
    public static function save_progress(array $data, string $filename = PARSER_NAME){
        Utils::save_json($data,  ROOT. "tmp/{$filename}", false);
    }
    public static function pause(int $sec){
        print("timeout {$sec}s...".PHP_EOL);
        sleep($sec);
    }
}
