<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\kontrolery;

use app\modely\Log;
use libs\Kontroler;
use libs\Pohled;
use app\modely\Spravceuzivatelu;
use app\modely\Upozorneni;
use Picqer\Barcode\BarcodeGeneratorHTML;
use Picqer\Barcode\Exceptions\InvalidCheckDigitException;

/**
 * Description of UzivatelKontroler
 *
 * @author František
 */
class Uzivatel extends Kontroler {


    /**
     * @var Spravceuzivatelu
     */
    private $su;

    public function __construct() {
        $this->setSablonu('app/sablony/vychozi');
        try {
            $this->su = $this->vytvorSpravce("uzivatelu");
        } catch (\Exception $e) {
            throw $e;
        }
        if (!empty($_SESSION['uzivatel'])) {
            if (!empty($_SESSION['uzivatel_login']) && $_SESSION['uzivatel_login'] + (60 * 10) > time()) {
                $_SESSION['uzivatel_login'] = time() + (60 * 10);
            } elseif (!empty($_SESSION['uzivatel_login']) && $_SESSION['uzivatel_login'] + (60 * 10) < time()) {
                $this->odhlaseni();
            }
            $_SESSION['uzivatel']->setAdmin($this->su->vratAdmin($_SESSION['uzivatel']->getId()));
        }
    }

    public function index() {
        if ($this->su->jePrihlasen()) {
            $this->sablona->set('titulek', 'Uzivatel');
            $content = new Pohled('app/pohledy/uzivatel');
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        } else {
            $this->prihlas();
        }
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

    public function prihlas($parametry = null) {
        $heslo = isset($parametry['heslo']) ? $parametry['heslo'] : null;
        $jmeno = isset($parametry['jmeno']) ? $parametry['jmeno'] : null;

        if ($this->su->jePrihlasen()) {
            $this->sablona->set('titulek', 'Uzivatel');
            $content = new Pohled('app/pohledy/uzivatel');
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        } elseif ($jmeno && $heslo) {
            $heslo = hash("sha256", $heslo);
            $uzivatel = $this->su->overUzivatele($jmeno, $heslo, $_SESSION[SESSION_POBOCKA]->getIdPobocka());
            if (is_object($uzivatel)) {
                $_SESSION['uzivatel_login'] = time();
                $_SESSION['uzivatel'] = $uzivatel;
                $content = new Pohled('app/pohledy/uzivatel');
                $this->sablona->set('upozorneni', new Upozorneni('success', 'OK'));
            } else {
                $content = new Pohled('app/pohledy/prihlaseni');
                $this->sablona->set('upozorneni', new Upozorneni('warning', 'Spatna kombinace osobniho cisla a hesla.'));
            }
            $this->sablona->set('titulek', 'Prihlaseni');
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        } else {
            $this->sablona->set('titulek', 'Prihlaseni');
            $content = new Pohled('app/pohledy/prihlaseni');
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        }
    }

    public function zmenheslo($parametry = null) {

        if (!$this->su->jePrihlasen()) {
            // pokud neni prihlasen - presmeruj
            $this->index();
            return true;
        } elseif (empty($parametry)) {
            // prazdne parametry
            $content = new Pohled('app/pohledy/zmenahesla');
            $this->sablona->set('titulek', 'Zmena hesla');
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        } else {
            // je prihlasen a je odeslan nejaky parametr
            $stare = !empty($parametry['stare']) ? $parametry['stare'] : null;
            $nove1 = !empty($parametry['nove1']) ? $parametry['nove1'] : null;
            $nove2 = !empty($parametry['nove2']) ? $parametry['nove2'] : null;

            // zkus overit uzivatele
            $uzivatel = $this->su->overUzivatele($_SESSION['uzivatel']->getOscislo(), hash('sha256', $stare), $_SESSION['uzivatel']->getPobocka());

            // stare heslo sedi k prihlasenemu uzivateli
            if (is_object($uzivatel)) {
                // nova hesla se shoduji
                if (hash('sha256', $nove1) === hash('sha256', $nove2)) {
                    // nove heslo je validni
                    if ($this->su->jeValidniHeslo($_SESSION['uzivatel']->getOscislo(), $nove1)) {
                        // zmena hesla
                        if ($this->su->zmenHeslo($_SESSION['uzivatel']->getId(), hash('sha256', $nove1))) {
                            $content = new Pohled('app/pohledy/uzivatel');
                            $this->sablona->set('titulek', 'Uzivatel');
                            $this->sablona->set('upozorneni', new Upozorneni('success', 'Zmena hesla probehla uspesne.'));
                        } else {
                            $this->sablona->set('titulek', 'Zmena hesla');
                            $content = new Pohled('app/pohledy/zmenahesla');
                            $this->sablona->set('upozorneni', new Upozorneni('danger', 'Zmena hesla se nepovedla - nevim proc.'));
                        }
                    } else {
                        $this->sablona->set('titulek', 'Zmena hesla');
                        $content = new Pohled('app/pohledy/zmenahesla');
                        $this->sablona->set('upozorneni', new Upozorneni('warning', 'Nove heslo nesplnuje podminky. <ul><li>nove heslo musi zacinat tvym osobnim cislem</li><li>heslo je delsi jak tve osobni cislo</li><li>heslo obsahuje znaky ze sady cisel [0-9] a pismen [a-zA-Z]</li></ul>'));
                    }
                } else {
                    $this->sablona->set('titulek', 'Zmena hesla');
                    $content = new Pohled('app/pohledy/zmenahesla');
                    $this->sablona->set('upozorneni', new Upozorneni('warning', 'Nova hesla se neshoduji.'));
                }
            } else {
                $this->sablona->set('titulek', 'Zmena hesla');
                $content = new Pohled('app/pohledy/zmenahesla');
                $this->sablona->set('upozorneni', new Upozorneni('danger', 'Spatne heslo.'));
            }


            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        }
        return true;
    }

    public function resetuj($parametry = null) {

        if (!$this->su->jePrihlasen() || !$_SESSION['uzivatel']->getAdmin()) {
            $this->index();
            return true;
        } elseif (empty($parametry)) {
        // nedelej nic, vsechno na konci metody
        } elseif (!empty($parametry['heslo'] && !empty($parametry['id']))) {
            // pokud mame zadane parametry
            if (is_object($this->su->overUzivatele($_SESSION['uzivatel']->getOscislo(), hash('sha256', $parametry['heslo']), $_SESSION['uzivatel']->getPobocka()))) {
                // overeni uzivatele, ktery zada o reset jineho uzivatele
                $uzivatel = $this->su->vratUzivatele($parametry['id']); # uzivatel, kteremu menime heslo
                if ($uzivatel->getPobocka() == $_SESSION[SESSION_POBOCKA]->getIdPobocka() && $uzivatel->getId() != $_SESSION['uzivatel']->getId()) {
                    // pokud sedi pobocka a nemenime heslo sobe
                    $nove = $uzivatel->getOscislo() . '' . bin2hex(random_bytes(4)); # osobni cislo a nahodny retezec jako nove heslo
                    if ($this->su->zmenHeslo($parametry['id'], hash('sha256', $nove))) {
                        $this->sablona->set('upozorneni', new Upozorneni('success', 'Pro uzivatele s ID ' . $parametry['id'] . ' bylo zvoleno nove heslo <b>' . $nove . '</b>'));
                        $log = new Log($_SESSION['uzivatel']->getOscislo() . ' vyresetoval heslo pro ' . $uzivatel->getOscislo(), 1);
                        $log->zapisLog();
                    } else {
                        $this->sablona->set('upozorneni', new Upozorneni('danger', 'Nastaveni noveho hesla selhalo.'));
                    }
                } else {
                    $this->sablona->set('upozorneni', new Upozorneni('warning', 'Sam sebe menit nemuzes.'));
                }
            } else {
                $this->sablona->set('upozorneni', new Upozorneni('danger', 'Spatne heslo'));
            }
        }
        $content = new Pohled('app/pohledy/resethesla');
        $seznam = $this->su->vratAktivniUzivatele($_SESSION[SESSION_POBOCKA]->getIDPobocka());
        $content->set('seznam', $seznam);
        $this->sablona->set('titulek', 'Reset hesla');
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }

    public function povys($parametry = null) {
        if (!$this->su->jePrihlasen() || !$_SESSION['uzivatel']->getAdmin()) {
            $this->index();
            return true;
        } elseif (empty($parametry)) {
            $seznam = $this->su->vratAktivniUzivatele($_SESSION[SESSION_POBOCKA]->getIDPobocka());
            $this->sablona->set('titulek', 'Seznam Uzivatelu');
            $content = new Pohled('app/pohledy/seznamuzivatelu');
            $content->set('seznam', $seznam);
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        } elseif (!empty($parametry['heslo']) && !empty($parametry['id'])) {
            if (is_object($this->su->overUzivatele($_SESSION['uzivatel']->getOscislo(), hash('sha256', $parametry['heslo']), $_SESSION['uzivatel']->getPobocka()))) {
                $uzivatel = $this->su->vratUzivatele($parametry['id']);
                if ($uzivatel->getPobocka() == $_SESSION[SESSION_POBOCKA]->getIdPobocka() && $uzivatel->getId() != $_SESSION['uzivatel']->getId()) {
                    $stav = $uzivatel->getAdmin() ? 0 : 1;
                    $this->su->zmenAdmin($uzivatel->getId(), $stav);
                    $log = new Log($_SESSION['uzivatel']->getOscislo() . ' zmenil stav admin pro ' . $uzivatel->getOscislo() . ' na ' . $stav, 1);
                    $log->zapisLog();
                } else {
                    $this->sablona->set('upozorneni', new Upozorneni('warning', 'Sam sebe menit nemuzes.'));
                }
            } else {
                $this->sablona->set('upozorneni', new Upozorneni('danger', 'Spatne heslo'));
            }
            $this->sablona->set('titulek', 'Seznam Uzivatelu');
            $content = new Pohled('app/pohledy/seznamuzivatelu');
            $seznam = $this->su->vratAktivniUzivatele($_SESSION[SESSION_POBOCKA]->getIDPobocka());
            $content->set('seznam', $seznam);
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        }
        return true;
    }

    /**
     * @param null $parametry
     * @return bool
     * @throws \libs\Exception
     */
    public function pridejuzivatele($parametry = null) {
        if (!$this->su->jePrihlasen() || !$_SESSION['uzivatel']->getAdmin()) {
            $this->index();
            return true;
        } elseif (empty($parametry)) {
            // prazdne parametry
            $this->sablona->set('titulek', 'Pridej uzivatele');
            $content = new Pohled('app/pohledy/pridejuzivatele');
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        } else {
            $pur = new \HTMLPurifier(); # vytvorime purifier

            // ocistime vstupy
            $oscislo = !empty($parametry['oscislo']) ? $parametry['oscislo'] : null;
            $jmeno = !empty($parametry['jmeno']) ? $pur->purify($parametry['jmeno']) : null;
            $heslo = !empty($parametry['heslo']) ? $parametry['heslo'] : null;
            $email = !empty($parametry['email']) ? $pur->purify($parametry['email']) : null;

            if (is_numeric($oscislo) && $jmeno && $this->su->jeValidniHeslo($oscislo, $heslo) && $email && $this->su->zjistiUnikatnostOscisla($oscislo) == false) {
                // kontrola po ocisteni vstupu pro spravny format
                $result = $this->su->pridejUzivatele($oscislo, $jmeno, hash('sha256', $heslo), $email);
                if ($result) {
                    $this->sablona->set('upozorneni', new Upozorneni('success', 'Uzivatel byl pridan.'));
                    $log = new Log($_SESSION['uzivatel']->getOscislo() . ' pridal uzivatele ' . $oscislo, 1);
                    $log->zapisLog();
                } else {
                    $this->sablona->set('upozorneni', new Upozorneni('danger', 'Uzivatel nebyl pridan.'));
                }
            } else {
                $this->sablona->set('upozorneni', new Upozorneni('warning', 'Osobni cislo neni cislo, nebo jiz je v databazi. Splnuje heslo podminky pro heslo? <ul><li>heslo musi zacinat tvym osobnim cislem</li><li>heslo musi byt delsi jak tve osobni cislo</li><li>heslo musi obsahovat pouze sadu cislic [0-9] a znaku [a-zA-Z]</li></ul>'));
            }

            $this->sablona->set('titulek', 'Pridej uzivatele');
            $content = new Pohled('app/pohledy/pridejuzivatele');
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        }
        return true;
    }

    public function odhlaseni() {
        $_SESSION['uzivatel'] = null;
        $_SESSION['uzivatel_login'] = null;
        if (!$this->su->jePrihlasen()) {
            $this->sablona->set('upozorneni', new Upozorneni('info', 'ODHLASEN'));
        } else {
            $this->sablona->set('upozorneni', new Upozorneni('danger', 'Odhlaseni se nepodarilo.'));
        }
        $content = new Pohled('app/pohledy/prihlaseni');
        $this->sablona->set('titulek', 'Odhlaseni');
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }

    public function ziskejEanHeslo($parametry = null) {
        $vyska = !empty($parametry['vyska']) ? $parametry['vyska'] : null;
        $sirka = !empty($parametry['sirka']) ? $parametry['sirka'] : null;
        $heslo1 = !empty($parametry['heslo1']) ? $parametry['heslo1'] : null;
        $heslo2 = !empty($parametry['heslo2']) ? $parametry['heslo2'] : null;
        $heslo = !empty($parametry['heslo']) ? $parametry['heslo'] : null;

        $content = new Pohled('app/pohledy/uzivatelean');

        if (!$this->su->jePrihlasen()) {
            $this->index();
            return true;
        } elseif ($vyska && $sirka && $heslo1 && $heslo2) {
            if (is_numeric($heslo1) && strlen((string)$heslo1) == 12 && $heslo1 === $heslo2) {
                $generator = new BarcodeGeneratorHTML();
                try {
                    $heslo = $heslo1 . $this->ean_checkdigit($heslo1);
                    if (!$this->su->jeValidniHeslo($_SESSION['uzivatel']->getOscislo(), $heslo)) {
                        throw new InvalidCheckDigitException('Spatny format hesla');
                    }
                    $uzivatel = $this->su->zmenHeslo($_SESSION['uzivatel']->getId(), hash('sha256', $heslo));
                    if (!$uzivatel) {
                        throw new InvalidCheckDigitException('Selhal zapis do db');
                    }
                    $ean = $generator->getBarcode($heslo, $generator::TYPE_EAN_13, $sirka, $vyska);
                    $content = new Pohled('app/pohledy/uzivatelean');
                    $this->sablona->set('titulek', 'Ean Heslo');
                    $content->set('eankod', $ean);
                    $content->set('eancislo', $heslo);
                    $this->sablona->set('content', $content->rendruj());
                    echo $this->sablona->rendruj();
                    return true;
                } catch (InvalidCheckDigitException $e) {
                    $this->sablona->set('upozorneni', new Upozorneni('danger', 'Nelze vytvorit EAN kod. Splnuje heslo podminky? <ul><li>presne 12 cislic</li><li>musi zacinat tvym osobnim cislem</li></ul>'));
                }
            } else {
                $this->sablona->set('upozorneni', new Upozorneni('danger', 'Heslo neni cislo, heslo nema 12 cisel, nebo se hesla neshoduji.'));
            }
        } elseif ($heslo) {
            $uzivatel = $this->su->overUzivatele($_SESSION['uzivatel']->getOscislo(), hash('sha256', $heslo), $_SESSION['uzivatel']->getPobocka());

            if (!is_object($uzivatel)) {
                $this->sablona->set('upozorneni', new Upozorneni('danger', 'Spatne heslo'));
                $content = new Pohled('app/pohledy/uzivatel');
                $this->sablona->set('titulek', 'Uzivatel');
                $this->sablona->set('content', $content->rendruj());
                echo $this->sablona->rendruj();
                return true;
            }
            $generator = new BarcodeGeneratorHTML();
            try {
                if (!is_numeric($heslo) || strlen((string)$heslo) != 13) {
                    throw new InvalidCheckDigitException;
                }
                $ean = $generator->getBarcode($heslo, $generator::TYPE_EAN_13, 2, 30);
                $content->set('eankod', $ean);
                $content->set('eancislo', $heslo);
            } catch (InvalidCheckDigitException $e) {
                $content->set('eancislo', "VYPLN PRO ZISKANI NOVEHO EAN13 HESLA");
            }
        }


        $this->sablona->set('titulek', 'Uzivatel');
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
        return true;
    }

    /**
     * @param $code
     * @return int
     */
    private function ean_checkdigit($code) {
        $code = str_pad($code, 12, "0", STR_PAD_LEFT);
        $sum = 0;
        for ($i = (strlen($code) - 1); $i >= 0; $i--) {
            $sum += (($i % 2) * 2 + 1) * $code[$i];
        }
        return (10 - ($sum % 10));
    }

}
