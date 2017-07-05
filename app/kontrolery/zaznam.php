<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\kontrolery;

use app\modely\Spravceuzivatelu;
use app\modely\Spravcezaznamu;
use app\modely\Spravcezbozi;
use libs\Kontroler;
use libs\Pohled;
use app\modely\Upozorneni;


/**
 * Description of ZaznamKontroler
 *
 * @author F@nny
 */
class zaznam extends Kontroler {

    /**
     * @var null|Spravcezaznamu
     */
    private $sz = null;
    /**
     * @var null|Spravcezbozi
     */
    private $szb = null;
    /**
     * @var null|Spravceuzivatelu
     */
    private $su = null;

    public function __construct() {
        $this->setSablonu('app/sablony/vychozi');
        try {
            $this->sz = $this->vytvorSpravce("zaznamu");
            $this->szb = $this->vytvorSpravce("zbozi");
            $this->su = $this->vytvorSpravce("uzivatelu");
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function index() {
        $this->pridej();
    }

    public function prehled($parametry = null) {

        $content = new Pohled('app/pohledy/prehled');

        if (!empty($parametry)) {

            if (isset($parametry['ean'])) {
                $zaznamy = $this->sz->vratVsechnyZaznamyEan($parametry['ean']);
            } elseif (isset($parametry['ora'])) {
                $zbozi = $this->szb->vratZboziOra($parametry['ora']);
                $zaznamy = $this->sz->vratVsechnyZaznamyEan($zbozi->getEan());
            }

            if (!empty($zaznamy)) {
                $this->sablona->set('upozorneni', new Upozorneni('success', "celkem " . sizeof($zaznamy) . " zaznamu"));
                $content->set('zaznamy', $zaznamy);
            } else {
                $this->sablona->set('upozorneni', new Upozorneni('warning', "zadny zaznam"));
            }

        }

        $this->sablona->set('titulek', 'Prehled');
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
            $vstupy[$k] = !empty(trim($vstupy[$k])) ? $purifier->purify(trim($vstupy[$k])) : null;
        }
        return $vstupy;
    }

    /**
     * kontrola zadani vstupu
     * kontrola neprazdnych vstupu
     * kontrola pridani zaznamu
     * @throws \libs\Exception
     */
    public function pridej($parametry = null) {

        $this->sablona->set('titulek', 'Pridej');
        $content = new Pohled('app/pohledy/pridanizaznamu');
        $posledniZaznamy = $this->vratPosledniZaznamy(10);
//
        $content->set('posledniZaznamy', $posledniZaznamy);


        if (!empty($parametry['token']) && false) {

            if (!$this->validateFormToken($parametry['token'])) {
                $this->pridej();
                exit();
            }

            $select = '';
            $text = '';
            $heslo = '';
            $faktura = '';
            $ean = '';
            $imei1 = '';
            $imei2 = '';
            $submit = '';
            $cetnost = '';
            $kusy = '';

            $vstupy = $this->zkontrolujVstupy($parametry);
            extract($vstupy);

            // nesmi byt nullovy
            if ($select && $heslo && $ean && $submit && $cetnost && $kusy) {
                $uzivatel = $this->su->overHeslo(hash('sha256', $heslo), $_SESSION[SESSION_POBOCKA]);
                // uzivatel existuje
                if (is_object($uzivatel) && $uzivatel->getAktivni() != 0) {

                    // je vydavano
                    if ($submit == "Vydej") {

                        // je to telefon s imei
                        if ($imei1 != null) {
                            $zaznam = $this->sz->vratPosledniPrijem($ean, $imei1, $_SESSION[SESSION_POBOCKA]);

                            // existuje zaznam o pridani do db
                            if (is_object($zaznam)) {

                                // zaznam ma i druhe imei
                                if ($zaznam->getImei1() != null && $zaznam->getImei2() != null) {
                                    // setnem spravne imei
                                    $imei1 = $zaznam->getImei1();
                                    $imei2 = $zaznam->getImei2();
                                }
                            }
                        }
                        // setnuti kusu do minusu
                        $kusy *= -1;

                        $ovlivneno = $this->sz->pridejZaznam($ean, $imei1, $imei2, $kusy, $uzivatel->getId(), $text, $select, $faktura, $_SESSION[SESSION_POBOCKA]);
                        if ($ovlivneno) {
                            $this->sablona->set('upozorneni', new Upozorneni('success', "zbozi vydano"));
                        } else {
                            $this->sablona->set('upozorneni', new Upozorneni('danger', "neco se podelalo u vydeje"));
                        }

                    } elseif ($submit == "Prijem") {// prijem

                        if ($imei1) {
                            // neni tam uz pridanej vickrat, nez vydanej?
                            $suma = $this->sz->vratSumuImei($ean, $imei1, $_SESSION[SESSION_POBOCKA]);
                        } else {
                            $suma = 0;
                        }
                        // pokud rovno 0 nebo null, muzem pridat
                        if ($suma == 0) {
                            $ovlivneno = $this->sz->pridejZaznam($ean, $imei1, $imei2, $kusy, $uzivatel->getId(), $text, $select, $faktura, $_SESSION[SESSION_POBOCKA]);

                            if ($ovlivneno) {
                                $this->sablona->set('upozorneni', new Upozorneni('success', "zbozi pridano"));
                            } else {
                                $this->sablona->set('upozorneni', new Upozorneni('danger', "neco se podelalo u prijmu"));
                            }
                        } else {
                            $this->sablona->set('upozorneni', new Upozorneni('danger', "tenhle imei je uz pridanej"));
                        }
                    }

                    // vicenasobny prijem/vydej
                    if ($cetnost === "VICENASOBNY") {
                        $content->set('select', $select);
                        $content->set('text', $text);
                        $content->set('heslo', $heslo);
                        $content->set('cetnost', $cetnost);
                    }

                } else {
                    $this->sablona->set('upozorneni', new Upozorneni('danger', "spatne heslo"));
                }
            } else {
                $this->sablona->set('upozorneni', new Upozorneni('danger', "neco mi chybi"));
            }
        }

        $formToken = $this->getFormToken();
        $content->set('formToken', $formToken);
        $_SESSION[FORMTOKEN] = $formToken;

        $this->sablona->set('content', $content->rendruj());

        echo $this->sablona->rendruj();
    }

    public function vratInfoZbozi() {
        $ean = !empty($_REQUEST['ean']) ? $_REQUEST['ean'] : null;
        if (is_numeric($ean)) {
            try {
                $vstupy = $this->zkontrolujVstupy(array('ean' => $ean));
                $zbozi = $this->szb->vratZboziEan($vstupy['ean']);
                if (is_object($zbozi)) {
                    $dual = $this->szb->zjistiDualsim($zbozi->getZbozi());
                    $zbozi->setDualsim($dual->dualsim);
                    echo json_encode($zbozi);
                } else {
                    echo json_encode(false);
                }
            } catch (\Exception $e) {
                echo json_encode(false);
            }
        } else {
            echo json_encode(false);
        }
    }

    public function vratPosledniZaznamy($pocet) {
        if (!empty($pocet) && is_numeric($pocet)) {
            return $this->sz->vratZaznamy($pocet, $_SESSION[SESSION_POBOCKA]);
        }
    }

}

?>