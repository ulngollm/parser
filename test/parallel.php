<?php
include_once(__DIR__ . '/../config.php');

echo 'Start ' . getmypid() . PHP_EOL;
//here 2 parallel scripts work
$elements = Utils::load_from_json('tmp/elem.json');
$urls = array_column($elements, 'link');
$sections = array_chunk($urls, 100);
// $elem = array_splice($elements, 0, 100);
$xpath = '//h1[@class="header_title"]';

foreach ($sections as $urls) {
    $pids = [];
    foreach ($urls as $key => $url) {
        // echo 'Parallel start ' . PHP_EOL;
        $pid = pcntl_fork();
        if ($pid) {
            $pids[] = $pid;
            // echo 'Parent proc';
            if ($key == array_key_last($urls)) {
                foreach ($pids as $pid) {
                    pcntl_waitpid($pid, $status);
                    echo $status . PHP_EOL;
                }
            }
        } else {
            echo BASE_URL . $url;
            echo PHP_EOL;
            $parser = new Offer(BASE_URL . $url);
            echo $parser->get_name($xpath) . PHP_EOL;
            die();
        }
    }
}
echo memory_get_peak_usage(true) / 1024 / 1024;
