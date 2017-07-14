<?php

use app\Bootstrap;


require('app/config.php');
require_once('app/error_handler.php');

//nastav autoloading
spl_autoload_extensions('.php');
spl_autoload_register();
session_start();

require_once 'vendor/autoload.php';

$app = new Bootstrap();

?>