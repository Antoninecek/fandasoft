<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\kontrolery;

use libs\Kontroler;
use libs\Pohled;
use app\modely\Spravceuzivatelu;
use app\modely\Upozorneni;
use PDOException;

/**
 * Description of UzivatelKontroler
 *
 * @author František
 */
class Uzivatel extends Kontroler {

    public function __construct() {
        $this->setSablonu('app/sablony/vychozi');
    }

    private function vytvorspravceuzivatelu(){
        try{
            $spravce = new spravceUzivatelu();
        } catch (PDOException $e){
            $handler = new Error();
            $handler->database();
            exit();
        }
        return $spravce;
    }

    public function index() {

        $this->sablona->set('titulek', 'Uzivatel');

        if (isset($_SESSION['uzivatel'])) {
            $content = new Pohled('app/pohledy/uzivatel');
        } else {
            $content = new Pohled('app/pohledy/prihlaseni');
        }
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }

    public function registrace() {
        if (isset($_SESSION['uzivatel'])) {
            $this->index();
            return true;
        }

        $heslo = isset($_POST['pwd']) ? $_POST['pwd'] : null;
        $jmeno = isset($_POST['jmeno']) ? $_POST['jmeno'] : null;

        if($jmeno && $heslo){
            $su = $this->vytvorspravceuzivatelu();
            $vstupy = $this->zkontrolujVstupy(array('jmeno' => $jmeno, 'heslo' => $heslo));
            if($vstupy) {
                extract($vstupy);
                if (!$su->vratId($jmeno)) {
                    $id = $su->zapisUzivatele(array("jmeno" => $jmeno, "heslo" => hash("sha256", $heslo), 'opravneni' => 2));
                    if ($id > 0) {
                        $this->sablona->set('upozorneni', new Upozorneni('success', 'zaregistrovan'));
                    } else {
                        $this->sablona->set('upozorneni', new Upozorneni('danger', 'nezaregistrovan'));
                    }
                    $content = new Pohled('app/pohledy/prihlaseni');
                } else {
                    $content = new Pohled('app/pohledy/registrace');
                    $this->sablona->set('upozorneni', new Upozorneni('warning', 'jmeno uz existuje'));
                }
            } else {
                $content = new Pohled('app/pohledy/registrace');
                $this->sablona->set('upozorneni', new Upozorneni('danger', 'nezaregistrovan'));
            }
        } else {
            $content = new Pohled('app/pohledy/registrace');
        }
        $this->sablona->set('titulek', 'Registrace');
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }

    /**
     * Ocisti vstupy od whitespaces
     * v pripade prazdneho vstupu vraci false
     * v pripade spravnych vstupu vrati asociovany pole
     * @param array $vstupy
     * @return array|bool
     */
    private function zkontrolujVstupy($vstupy = array()) {
        $purifier = new \HTMLPurifier();
        foreach ($vstupy as $k => $v) {
            $vstupy[$k] = !empty($vstupy[$k]) ? $purifier->purify($vstupy[$k]) : null;
            if (empty($vstupy[$k]) || preg_match('/\s/', $vstupy[$k])) {
                return false;
            }
        }
        return $vstupy;
    }

    public function prihlaseni() {
        $heslo = isset($_POST['pwd']) ? $_POST['pwd'] : null;
        $jmeno = isset($_POST['jmeno']) ? $_POST['jmeno'] : null;
        $heslo = hash("sha256", $heslo);
        $su = $this->vytvorspravceuzivatelu();
        if($jmeno && $heslo) {
            $uzivatel = $su->overUzivatele(array("jmeno" => $jmeno, "heslo" => $heslo));
            if ($uzivatel) {
                session_unset();
                $_SESSION['log_time'] = time();
                $_SESSION['uzivatel'] = $uzivatel;
                $content = new Pohled('app/pohledy/uzivatel');
                $this->sablona->set('upozorneni', new Upozorneni('success', 'OK'));
            } else {
                $content = new Pohled('app/pohledy/prihlaseni');
                $this->sablona->set('upozorneni', new Upozorneni('warning', 'NOT OK'));
            }
            $this->sablona->set('titulek', 'Prihlaseni');
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        } else {
            $this->index();
        }
    }

    public function odhlaseni() {
        if(isset($_SESSION['uzivatel'])) {
            $this->sablona->set('upozorneni', new Upozorneni('info', 'ODHLASEN'));
        }
        session_unset();
        $content = new Pohled('app/pohledy/prihlaseni');
        $this->sablona->set('titulek', 'Odhlaseni');
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }

}
