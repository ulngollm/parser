<?php

class Downloader
{
    public static function get_page($url): string
    {
        $page = self::load_from_cache($url) ?? self::download_page($url);
        return $page;
    }

    private static function load_from_cache($url): ?string
    {

        if (defined('CACHE_ENABLE') && CACHE_ENABLE) {
            $filename = self::get_cached_name($url);
            if (file_exists($filename)) {
                return file_get_contents($filename);
            }
        } else return null;
    }

    private static function get_cached_name($url): string
    {
        return ROOT . "/cache/" . md5($url) . ".html";
    }

    private static function download_page($url)
    {
        $id = curl_init($url);
        curl_setopt($id, CURLOPT_RETURNTRANSFER, 1);
        $page = curl_exec($id);
        curl_close($id);
        self::save_to_cache($url, $page);
        return $page;
    }

    private static function save_to_cache($url, $page): void
    {
        $filename = self::get_cached_name($url);
        file_put_contents($filename, $page);
    }
    public static function remove_from_cache($url)
    {
        $filename = self::get_cached_name($url);
        unlink($filename);
    }
}
