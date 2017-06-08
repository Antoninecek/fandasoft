<?php


ini_set('display_errors', 1); // display errors
error_reporting(E_ALL);

use app\Bootstrap;


require('app/config.php');


//nastav autoloading
spl_autoload_extensions('.php');
spl_autoload_register();
session_start();
// if( isset( $_SESSION[SESSION_PRIDEJ_PRIHLASENI] ) && $_SESSION[SESSION_PRIDEJ_PRIHLASENI] - time() > 0 )
// {
//    unset($_SESSION[SESSION_PRIDEJ_PRIHLASENI]);
// }
require_once 'vendor/autoload.php';
$app = new Bootstrap();

?>