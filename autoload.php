<?php
error_reporting(E_ALL);
// ini_set('error_log', __DIR__ . "/../log/$parser_name.log");

function loadClass($class_name)
{
    $path = ROOT."/classes/".strtolower($class_name).".php";
    include_once($path);
}
spl_autoload_register('loadClass');
