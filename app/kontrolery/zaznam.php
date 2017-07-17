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
        $id = !empty($parametry['id']) ? trim($parametry['id']) : null;
        if (is_numeric($id)) {
            if (strlen((string)$id) > 10) {
                $zaznamy = $this->sz->vratVsechnyZaznamyEan($id, $_SESSION[SESSION_POBOCKA]->getId());
            } else {
                $zbozi = $this->szb->vratZboziOra($id);
                if (is_object($zbozi)) {
                    $zaznamy = $this->sz->vratVsechnyZaznamyEan($zbozi->getEan(), $_SESSION[SESSION_POBOCKA]->getId());
                }
            }

            if (!empty($zaznamy)) {
                $this->sablona->set('upozorneni', new Upozorneni('success', "celkem " . sizeof($zaznamy) . " zaznamu pro dotaz " . $id));
                $content->set('zaznamy', $zaznamy);
            } else {
                $this->sablona->set('upozorneni', new Upozorneni('warning', "zadny zaznam pro dotaz " . $id));
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


    public function oprav($parametry = null) {

        if (!empty($parametry['formtoken']) && $this->validateFormToken($parametry['formtoken'])) {
            $uzivatel = $this->su->overUzivateleId(array('id' => $parametry['jmeno'], 'heslo' => hash('sha256', $parametry['heslo'])));
            if (is_object($uzivatel)) {
                $ean = null;
                $imei1 = null;
                $imei2 = null;
                $kusy = null;
                $jmeno = null;
                $text = null;
                $typ = null;
                $faktura = null;
                $pobocka = null;
                $vstupy = $this->zkontrolujVstupy($parametry);
                extract($vstupy);
                $result = $this->sz->pridejZaznam($ean, $imei1, $imei2, $kusy * -1, $jmeno, "OPRAVA - " . $text, $typ, $faktura, $pobocka);
                if ($result['ovlivneno']) {
                    $this->pridej(array('upozorneni' => new Upozorneni('success', "opraveno")));
                } else {
                    $this->pridej(array('upozorneni' => new Upozorneni('danger', "neopraveno, oprav to sam/a")));
                }
            } else {
                $this->pridej(array('upozorneni' => new Upozorneni('warning', "spatne heslo, oprav to sam/a")));
            }
        } elseif (!empty($parametry['id']) && $_SESSION['casProOpravu'] > time()) {
            $zaznam = $this->sz->vratZaznam($parametry['id']);
            $uzivatel = $this->su->vratUzivatele($zaznam->getJmeno());
            $token = $this->getFormToken();
            $content = new Pohled('app/pohledy/opravenizaznamu');
            $content->set('zaznam', $zaznam);
            $content->set('uzivatel', $uzivatel);
            $content->set('formtoken', $token);
            $_SESSION[FORMTOKEN] = $token;
            $this->sablona->set('titulek', 'Oprava');
            $this->sablona->set('content', $content->rendruj());
            echo $this->sablona->rendruj();
            exit();
        } elseif ($_SESSION['casProOpravu'] <= time()) {
            $this->pridej(array('upozorneni' => new Upozorneni('warning', "pozde, zadej vse znovu")));
            exit();
        } else {
            $this->index();
        }

    }

    /**
     * kontrola zadani vstupu
     * kontrola neprazdnych vstupu
     * kontrola pridani zaznamu
     * @param null $parametry
     * @throws \libs\Exception
     */
    public function pridej($parametry = null) {

        if (!empty($parametry['titulek'])) {
            $this->sablona->set('titulek', $parametry['titulek']);
        } else {
            $this->sablona->set('titulek', 'Pridej');
        }

        if (!empty($parametry['upozorneni'])) {
            $this->sablona->set('upozorneni', $parametry['upozorneni']);
        }

        $content = new Pohled('app/pohledy/pridanizaznamu');


        if (!empty($parametry['token'])) {

            if (!$this->validateFormToken($parametry['token'])) {
                $this->pridej();
                exit();
            }

            // pridani 20 sekund pro moznou opravu zaznamu
            $_SESSION['casProOpravu'] = time() + 20;

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

                        $result = $this->sz->pridejZaznam($ean, $imei1, $imei2, $kusy, $uzivatel->getId(), $text, $select, $faktura, $_SESSION[SESSION_POBOCKA]->getId());

                        if ($result['ovlivneno']) {
                            $textOpravy = "zaznam/oprav/?id=" . $result['lastid'];
                            $this->sablona->set('upozorneni', new Upozorneni('success', "zbozi vydano <a id='oprava-zaznamu' href='$textOpravy'>OPRAV ZAZNAM</a>"));
                        } else {
                            $this->sablona->set('upozorneni', new Upozorneni('danger', "neco se podelalo u vydeje"));
                        }

                    } elseif ($submit == "Prijem") {
                        // prijem

                        if ($imei1) {
                            // neni tam uz pridanej vickrat, nez vydanej?
                            $suma = $this->sz->vratSumuImei($ean, $imei1, $_SESSION[SESSION_POBOCKA]);
                        } else {
                            $suma = 0;
                        }
                        // pokud rovno 0 nebo null, muzem pridat
                        if ($suma == 0) {
                            $result = $this->sz->pridejZaznam($ean, $imei1, $imei2, $kusy, $uzivatel->getId(), $text, $select, $faktura, $_SESSION[SESSION_POBOCKA]->getId());

                            if ($result['ovlivneno']) {

                                // pridat do nevystaveno
                                $nevystaveno = $this->sz->vratNevystaveno($ean, $_SESSION[SESSION_POBOCKA]->getId()); # TODO asi by melo vratit obj. Zaznam
                                if ($nevystaveno) { # pokud to neco vratilo
                                    $noveKusy = (int)$nevystaveno->getKusy() + (int)$kusy; # soucet kusu
                                    $nevystavenoResult = $this->sz->updateNevystaveno($nevystaveno->getId(), $noveKusy); # TODO update nevystaveno podle id zaznamu a kusu
                                } else {
                                    $nevystavenoResult = $this->sz->pridejNevystaveno($ean, $kusy, $_SESSION[SESSION_POBOCKA]->getId()); # TODO pridani nevystaveno podle eanu, kusu a pobocky
                                }

                                if(!$nevystavenoResult){
                                    # TODO loguj chybu // TODO log chyb u prijmu
                                }

                                $textOpravy = "zaznam/oprav/?id=" . $result['lastid'];
                                $this->sablona->set('upozorneni', new Upozorneni('success', "zbozi pridano <a href='$textOpravy'>OPRAV ZAZNAM</a>"));
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
                    $content->set('formularZnovu', true);
                    $content->set('formularFaktura', $faktura);
                    $content->set('formularEan', $ean);
                    $content->set('formularImei1', $imei1);
                    $content->set('formularImei2', $imei2);
                    $content->set('formularKusy', $kusy);
                    $content->set('formularText', $text);
                    $content->set('formularSelect', $select);
                    $content->set('formularCetnost', $cetnost);
                }
            } else {
                $this->sablona->set('upozorneni', new Upozorneni('danger', "neco mi chybi"));
            }
        }

        $posledniZaznamy = $this->vratPosledniZaznamy(10);
        $content->set('posledniZaznamy', $posledniZaznamy);
        $formToken = $this->getFormToken();
        $content->set('formToken', $formToken);
        $_SESSION[FORMTOKEN] = $formToken;

        $this->sablona->set('content', $content->rendruj());

        echo $this->sablona->rendruj();
    }

    public function faktura($parametry = null) {
        $id = !empty($parametry['idzaznamu']) ? $parametry['idzaznamu'] : null;
        $cislo = !empty($parametry['faktura']) ? trim($parametry['faktura']) : null;
        if ($id && is_numeric($cislo)) {
            $zaznam = $this->sz->vratZaznam($id);
            if (is_object($zaznam) && $zaznam->getFaktura() == null) {
                $ovlivneno = $this->sz->updatniFakturu($id, $cislo);
                if ($ovlivneno) {
                    $this->pridej(array('upozorneni' => new Upozorneni('success', "faktura vlozena")));
                } else {
                    $this->pridej(array('upozorneni' => new Upozorneni('danger', "faktura nevlozena")));
                }
            } else {
                $this->pridej(array('upozorneni' => new Upozorneni('warning', "zaznam neni, nebo uz je nejaka faktura prirazena")));
            }
        }
    }

    public function vratInfoZbozi() {
        $ean = !empty($_REQUEST['ean']) ? $_REQUEST['ean'] : null;
        if (is_numeric($ean)) {
            try {
                $vstupy = $this->zkontrolujVstupy(array('ean' => $ean));
                $zbozi = $this->szb->vratZboziEan($vstupy['ean']);
                if (is_object($zbozi)) {
                    $dual = $this->szb->zjistiDualsim($zbozi->getZbozi());
                    if (is_object($dual)) {
                        $zbozi->setDualsim($dual->dualsim);
                    } else {
                        $zbozi->setDualsim(0);
                    }
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