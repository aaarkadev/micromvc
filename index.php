<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

define('APP_BASE', realpath(dirname(__FILE__)) . '/' );
 
require_once(APP_BASE.'src/App.php');
$config = require_once(APP_BASE.'config.php');

$appObj = new App($config);
$appObj->Run();
