<?php
error_reporting(E_ALL);

const PARSER_NAME = 'kzto';
const FILENAME = PARSER_NAME.".json";
const ROOT = '/mnt/c/Users/noknok/Documents/parser/catalog_parser';
const BASE_URL = 'https://www.home-heat.ru';
const DEBUG = false;
const OFFERS_EXPAND = false;
include_once(ROOT . '/autoload.php');