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

    public function registrace() {
        if (isset($_SESSION['uzivatel'])) {
            $this->index();
            return true;
        }

        $heslo = isset($_POST['pwd']) ? $_POST['pwd'] : null;
        $jmeno = isset($_POST['jmeno']) ? $_POST['jmeno'] : null;

        if ($jmeno && $heslo) {
            $su = $this->vytvorspravceuzivatelu();
            $vstupy = $this->zkontrolujVstupy(array('jmeno' => $jmeno, 'heslo' => $heslo));
            if ($vstupy) {
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
                $_SESSION['log_time'] = time();
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
            $this->index();
            return true;
        } elseif (empty($parametry)) {
            $content = new Pohled('app/pohledy/zmenahesla');
            $this->sablona->set('titulek', 'Zmena hesla');
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        } else {
            $stare = !empty($parametry['stare']) ? $parametry['stare'] : null;
            $nove1 = !empty($parametry['nove1']) ? $parametry['nove1'] : null;
            $nove2 = !empty($parametry['nove2']) ? $parametry['nove2'] : null;
            $uzivatel = $this->su->overUzivatele($_SESSION['uzivatel']->getOscislo(), hash('sha256', $stare), $_SESSION['uzivatel']->getPobocka());

            // stare heslo sedi k prihlasenemu uzivateli
            if (is_object($uzivatel)) {
                // nova hesla se shoduji
                if (hash('sha256', $nove1) === hash('sha256', $nove2)) {
                    // nove heslo je validni
                    if ($this->su->jeValidniHeslo($nove1)) {
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
                        $this->sablona->set('upozorneni', new Upozorneni('warning', 'Nove heslo nesplnuje podminky.'));
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

    public function povys($parametry = null) {
        if (!$this->su->jePrihlasen()) {
            $this->index();
            return true;
        } elseif (empty($parametry)) {
            $seznam = $this->su->vratAktivniUzivatele($_SESSION[SESSION_POBOCKA]->getIDPobocka());
            $this->sablona->set('titulek', 'Seznam Uzivatelu');
            $content = new Pohled('app/pohledy/seznamuzivatelu');
            $content->set('seznam', $seznam);
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        }
        return true;
    }

    public function pridejuzivatele($parametry = null) {
        if (!$this->su->jePrihlasen()) {
            $this->index();
            return true;
        } elseif (empty($parametry)) {
            $this->sablona->set('titulek', 'Pridej uzivatele');
            $content = new Pohled('app/pohledy/pridejuzivatele');
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
        } else {
            $pur = new \HTMLPurifier();

            $oscislo = !empty($parametry['oscislo']) ? $parametry['oscislo'] : null;
            $jmeno = !empty($parametry['jmeno']) ? $pur->purify($parametry['jmeno']) : null;
            $heslo = !empty($parametry['heslo']) ? $parametry['heslo'] : null;
            $email = !empty($parametry['email']) ? $pur->purify($parametry['email']) : null;

            if (is_numeric($oscislo) && $jmeno && $heslo && $email && $this->su->zjistiUnikatnostOscisla($oscislo) == false) {
                $result = $this->su->pridejUzivatele($oscislo, $jmeno, hash('sha256', $heslo), $email);
                if ($result) {
                    $this->sablona->set('upozorneni', new Upozorneni('success', 'Uzivatel byl pridan.'));
                } else {
                    $this->sablona->set('upozorneni', new Upozorneni('danger', 'Uzivatel nebyl pridan.'));
                }
            } else {
                $this->sablona->set('upozorneni', new Upozorneni('danger', 'Osobni cislo neni cislo, nebo jiz je v databazi.'));
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
                    if (!$this->su->jeValidniHeslo($heslo)) {
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
                    $this->sablona->set('upozorneni', new Upozorneni('danger', 'Nelze vytvorit EAN kod.'));
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
                $ean = $generator->getBarcode($heslo, $generator::TYPE_EAN_13, 2, 30);
                $content->set('eankod', $ean);
                $content->set('eancislo', $heslo);
            } catch (InvalidCheckDigitException $e) {

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
