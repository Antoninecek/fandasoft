<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 26.04.2017
 * Time: 12:18
 */

namespace app\modely;

use libs\Spravce;


class spravcezaznamu extends Spravce {

    public function vratPosledniPrijem($ean, $imei, $pobocka) {
        return $this->db->dotazObjekt('SELECT * FROM `zarizeni` where ean = ? and kusy > 0 and pobocka = ? and (imei1 = ? or imei2 = ?) ORDER BY `datum` DESC', 'zaznam', array($ean, $pobocka->getId(), $imei, $imei));
    }

    public function pridejZaznam($ean, $imei1, $imei2, $kusy, $jmeno, $text, $typ, $faktura, $pobocka) {
        return $this->db->dotaz('INSERT INTO zarizeni (ean, imei1, imei2, kusy, jmeno, text, typ, faktura, pobocka) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', array($ean, $imei1, $imei2, $kusy, $jmeno, $text, $typ, $faktura, $pobocka->getId()));
    }

    public function vratSumuImei($ean, $imei, $pobocka) {
        return $this->db->dotazJeden('SELECT SUM(kusy) FROM zarizeni WHERE ean = ? AND pobocka = ? AND (imei1 = ? OR imei2 = ?)', array($ean, $pobocka->getId(), $imei, $imei));
    }

    public function vratVsechnyZaznamyEan($ean, $pobocka) {
        return $this->db->dotazVsechnyObjekty('SELECT * FROM zarizeni WHERE ean = ? AND pobocka = ?', array($ean, $pobocka->getId()));
    }

    public function vratZaznamy($pocet, $pobocka) {
        return $this->db->dotazVsechnyObjekty('select A.id, A.ean, A.imei1, A.imei2, A.kusy, C.jmeno, A.text, A.typ, A.faktura, A.datum, B.zbozi, B.model, B.popis
from (select * from zarizeni where pobocka = ? order by datum desc limit ?) as A left join sap as B on A.ean = B.ean left join uzivatele as C on A.jmeno = C.id', 'zaznam', array($pobocka->getId(), $pocet));
    }

}