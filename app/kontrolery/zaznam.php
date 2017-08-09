<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\kontrolery;

use app\modely\Log;
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
                        if ($suma->kusy == 0) {
                            $result = $this->sz->pridejZaznam($ean, $imei1, $imei2, $kusy, $uzivatel->getId(), $text, $select, $faktura, $_SESSION[SESSION_POBOCKA]->getId());

                            if ($result['ovlivneno']) {
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

    public function vystav($parametry = null) {

        $cislo = empty($parametry['vystavZaznam']) ? null : $parametry['vystavZaznam'];

        $content = new Pohled('app/pohledy/vystav');

        if (!empty($cislo) && is_numeric($cislo)) {

            // musim si najit zbozi, abych mohl pracovat jen s orou - duplicitni zaznamy ora/ean
            $zbozi = null;
            if (strlen((string)$cislo) > 10) {
                $zbozi = $this->sz->vratZboziEan($cislo);
            } else {
                $zbozi = $this->sz->vratZboziOra($cislo);
            }

            if (is_object($zbozi)) {
                $zaznam = $this->sz->vratNevystavenoOra($zbozi->getZbozi(), $_SESSION[SESSION_POBOCKA]->getId());

                // zaznam existuje, staci update kusu
                if (is_object($zaznam)) {
                    $this->sz->updateKusyNevystaveno($zaznam->getId(), $zaznam->getKusy() + 1);
                    $this->zmenPriznak($zaznam, 0);
                } else {
                    $this->sz->pridejNevystaveno($zbozi->getZbozi(), 1, $_SESSION[SESSION_POBOCKA]->getId());
                }
                $this->sablona->set('upozorneni', new Upozorneni('success', "Pridano."));
            } else {
                $this->sablona->set('upozorneni', new Upozorneni('danger', "Neznamy ean/ora, je treba vyckat s timto zaznamem na pozdeji."));
                $log = new Log("Neznamy zaznam " . $cislo, 1);
                $log->zapisLog();
            }
        }

        $this->sablona->set('titulek', 'Vystav');
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }

    /**
     * rozhodovaci strom pro zmenu priznaku
     * @param $zaznam \app\modely\Zaznam
     * @param $priznak int pozadovanej priznak
     */
    private function zmenPriznak($zaznam, $priznak) {
        if ($zaznam->getPriznak() != 2) {
            $this->sz->zmenPriznakNevystaveno($priznak, $zaznam->getId());
        }
    }

    public function zmenPriznakAjax($parametry = null){
        if(!empty($parametry['ora']) && isset($parametry['priznak']) && is_numeric($parametry['ora']) && is_numeric($parametry['priznak'])){
            $zaznam = $this->sz->vratNevystavenoOra($parametry['ora'], $_SESSION[SESSION_POBOCKA]->getId());
            if(!is_object($zaznam)){
                $this->sz->pridejNevystaveno($parametry['ora'], 0, $_SESSION[SESSION_POBOCKA]->getId());
                $zaznam = $this->sz->vratNevystavenoOra($parametry['ora'], $_SESSION[SESSION_POBOCKA]->getId());
                if(!is_object($zaznam)){
                    $log = new Log('kontrolery/zaznam zmenPriznakAjax', 1);
                    $log->zapisLog();
                    echo false;
                } else {
                    $this->sz->zmenPriznakNevystaveno($parametry['priznak'], $zaznam->getId());
                    echo true;
                }
            } else {
                $this->sz->zmenPriznakNevystaveno($parametry['priznak'], $zaznam->getId());
                echo true;
            }
        } else {
            echo false;
        }
    }

    public function vystavSap($parametry = null) {
        $seznam = $this->sz->vratVsehnyNevystaveno($_SESSION[SESSION_POBOCKA]->getId());

        if (!empty($parametry['vystavtoken']) && $parametry['vystavtoken'] == $_SESSION['vystavtoken']) {
            $ovlivneno = $this->sz->zmenSapVystaveno($_SESSION[SESSION_POBOCKA]->getId(), 1);
        } else {
            $ovlivneno = false;
        }

        $content = new Pohled('app/pohledy/vystavkontrola');

        $vystavtoken = $this->getFormToken();
        $_SESSION['vystavtoken'] = $vystavtoken;
        $content->set('vystavtoken', $vystavtoken);

        $zbozi = array();
        $ean = array();

        if ($ovlivneno) {
            foreach ($seznam as $s) {
                if ($s->getKusy() == 0) {
                    $this->sz->smazNevystaveno($s->getId());
                }

                if (!empty($s->getZbozi()) || !empty($s->getOra())) {
                    $zbozi[] = $s;
                } else {
                    $ean[] = $s;
                }
            }

            $to = EMAILTO;
            $subject = "#FANDASOFT - Vystaveno";
            $headers = "From: vystaveno@fandasoft.cz" . "\r\n";
            $headers .= "MIME-Version: 1.0\r\n";
            $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $emailZbozi = '';
            foreach ($zbozi as $z) {
                $zboziid = empty($z->getZbozi()) ? $z->getOra() : $z->getZbozi();
                $emailZbozi = $emailZbozi . $zboziid . " " . $z->getKusy() . "<br>";
            }

            $emailText = "<html><head><title>vystaveno</title></head><body><h1>ORA</h1>" . $emailZbozi . "</body></html>";

            mail($to, $subject, $emailText, $headers);

        }
        $content->set('seznamZbozi', $zbozi);
        $content->set('seznamEan', $ean);

        $seznam = $this->sz->vratVsehnyNevystaveno($_SESSION[SESSION_POBOCKA]->getId());
        $content->set('seznam', $seznam);

        $this->sablona->set('titulek', 'Vystav');
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }

    public function zabal($parametry = null) {
        $cislo = empty($parametry['vystavZaznam']) ? null : $parametry['vystavZaznam'];

        $content = new Pohled('app/pohledy/zabal');
        if (!empty($cislo) && is_numeric($cislo)) {

            $zbozi = null;
            if (strlen((string)$cislo) > 10) {
                $zbozi = $this->sz->vratZboziEan($cislo);
            } else {
                $zbozi = $this->sz->vratZboziOra($cislo);
            }

            if (is_object($zbozi)) {
                $zaznam = $this->sz->vratNevystavenoOra($zbozi->getZbozi(), $_SESSION[SESSION_POBOCKA]->getId());
                // zaznam existuje, staci update kusu
                if (is_object($zaznam)) {
                    $kusy = ($zaznam->getKusy() - 1) < 0 ? 0 : ($zaznam->getKusy() - 1);
                    $this->sz->updateKusyNevystaveno($zaznam->getId(), $kusy);
                    $this->zmenPriznak($zaznam, 0);
                } else {
                    $this->sz->pridejNevystaveno($zbozi->getZbozi(), -1, $_SESSION[SESSION_POBOCKA]->getId());
                }
                $this->sablona->set('upozorneni', new Upozorneni('success', "Pridano."));
            } else {
                $this->sablona->set('upozorneni', new Upozorneni('danger', "Neznamy ean/ora, je treba vyckat s timto zaznamem na pozdeji."));
                $log = new Log("Neznamy zaznam " . $cislo, 1);
                $log->zapisLog();
            }
        }

        $this->sablona->set('titulek', 'Zabal');
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }

    public function prehledVystav() {

        $seznam = $this->sz->vratVsechnyZaznamyNevystaveno($_SESSION[SESSION_POBOCKA]->getId());
        $kategorie = array();
        $content = new Pohled('app/pohledy/prehledvystav');
        $content->set('seznam', $seznam);
        $content->set('kategorie', $kategorie);
        $this->sablona->set('titulek', 'prehled vystav');
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