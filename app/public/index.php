<?php
session_start();

$base_url = 'http://localhost/';
$GLOBALS['base_url'] = $base_url;

require_once '../app/init.php';

$app = new App;
