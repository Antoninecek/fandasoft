<?php
if($_SERVER['SERVER_NAME'] == '127.0.0.1'){
    define('ENVIRONMENT', 'DEV');
} else if($_SERVER['SERVER_NAME'] == 'bonifac'){
    define('ENVIRONMENT', 'RPI');
} else {
    define('ENVIRONMENT', 'PROD');
}

if(ENVIRONMENT == 'DEV'){
    define('DEBUG', true);
    define('FORMTOKEN', 'formtoken');
    define('WEB_URL', '127.0.0.1');
    define('ROOT_DIR_FILES', '\\projects\\fandasoft\\');
    define('ROOT_DIR', '/projects/fandasoft/');
    define('FULL_PATH_ROOT', $_SERVER['SERVER_NAME'].ROOT_DIR);
    define('DB_TYPE', 'mysql');
    define('DB_HOST', '127.0.0.1');
    define('DB_NAME', 'sklad_db_jednodussi_n');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    //define('SESSION_CAS_PRIDANI');
    define('SESSION_POBOCKA', 'fandasoftpobocka');
    define('SESSION_PRIDEJ_PRIHLASENI', 'pridejprihlasen');
    define('SESSION_PRIDEJ_UZIVATEL', 'pridejuzivatel');
    define('SIGN_JWT', 'fandasoft');
    define('COOKIE_POBOCKA', 'FANDASOFTPOBOCKA');
} else if(ENVIRONMENT == 'RPI'){
    define('ROOT_DIR_FILES', '/');
    define('ROOT_DIR', '/');
    define('FULL_PATH_ROOT', $_SERVER['SERVER_NAME'].ROOT_DIR);
    define('DB_TYPE', 'mysql');
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'weaptodo');
    define('DB_USER', 'root');
    define('DB_PASS', 'root');
} else {

}

ini_set('default_charset',"utf-8");

//ini_set('session.cookie_lifetime', 60 * 60 * 24 * 7);
//ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 7);
