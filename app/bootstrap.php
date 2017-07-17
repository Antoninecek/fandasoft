<?php

namespace app;

use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
use app\modely\pobocka;

class Bootstrap {

    private $kontroler;
    private $akce;
    private $parametry = array();

    public function __construct() {
        $this->parsujUrl();
        $this->routuj();
    }

    private function routuj() {
        if ($this->kontroler) {
            $trida = "\\app\\kontrolery\\" . $this->kontroler;
            $soubor = "app/kontrolery/" . $this->kontroler . ".php";

            if (is_readable($soubor)) {
                $handler = new $trida();

                if ($this->akce && method_exists($handler, $this->akce)) {
                    $handler->{$this->akce}($this->parametry);
                    return true;
                } elseif (!$this->akce) {
                    $handler->index();
                    return true;
                } else {
                    $handler = new kontrolery\Error();
                    $handler->error404();
                    return true;
                }
            } else {
                $handler = new kontrolery\Error();
                $handler->error404();
                return true;
            }
        } else {
            $handler = new kontrolery\Home();
        }
        $handler->index();
    }

    private function parsujUrl() {
        $url = $_SERVER['REQUEST_URI'];
        $url = filter_var($url, FILTER_SANITIZE_URL);
        $pos = strpos($url, ROOT_DIR);
        if ($pos !== false) {
            $url = substr_replace($url, '', $pos, strlen(ROOT_DIR));
        }
        $parsovanaUrl = parse_url($url);

        $path = array_filter(explode('/', !empty($parsovanaUrl['path']) ? $parsovanaUrl['path'] : null));

        if (empty($path[0])) {
            $this->kontroler = null;
        } else if (count($path) > 2) {
            $this->kontroler = 'Error';
        } else {
            $this->kontroler = $path[0];
        }

        $this->akce = !empty($path[1]) ? $path[1] : null;

        if (!empty($parsovanaUrl['query'])) {
            preg_match_all('([\w]+=[\w\d]+)', $parsovanaUrl['query'], $par);
            foreach ($par[0] as $p) {
                $this->parametry[explode('=', $p)[0]] = explode('=', $p)[1];
            }

        }

        // POST jako parametry pro jednoduchou praci s formulari
        foreach ($_POST as $k => $v) {
            $this->parametry[$k] = $v;
        }


        $token = !empty($_COOKIE[COOKIE_POBOCKA]) ? $_COOKIE[COOKIE_POBOCKA] : null;
        $signer = new Sha256();

        $data = new ValidationData(); // It will use the current time to validate (iat, nbf and exp)
        $data->setIssuer(WEB_URL);
        $data->setAudience(WEB_URL);

        try {
            $token = (new Parser())->parse($token);
            if (!$token->verify($signer, SIGN_JWT) || !$token->validate($data)) {
                $this->presmeruj();
            } else {
                $pobocka = json_decode($token->getClaim("pobocka"));
                $_SESSION[SESSION_POBOCKA] = new Pobocka($pobocka->id, $pobocka->id_pobocka, $pobocka->nazev, $pobocka->heslo, $pobocka->mesto);
            }
        } catch (\Exception $exception) {
            $this->presmeruj();
        }

    }


    private function presmeruj() {
        if($this->kontroler == "zaznam" && $this->akce == "vratInfoZbozi"){

        }
        elseif ($this->kontroler != "pobocka" && $this->kontroler != "home") {
            $this->kontroler = 'pobocka';
            $this->akce = '';
            $this->parametry = '';
            unset($_SESSION['uzivatel']);
            unset($_SESSION[SESSION_POBOCKA]);
        }
    }

}

