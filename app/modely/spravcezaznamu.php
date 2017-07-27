<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 26.04.2017
 * Time: 12:18
 */

namespace app\modely;

use libs\Spravce;


class Spravcezaznamu extends Spravce {

    public function vratPosledniPrijem($ean, $imei, $pobocka) {
        return $this->db->dotazObjekt('SELECT * FROM `zarizeni` where ean = ? and kusy > 0 and pobocka = ? and (imei1 = ? or imei2 = ?) ORDER BY `datum` DESC', 'Zaznam', array($ean, $pobocka->getId(), $imei, $imei));
    }

    public function pridejZaznam($ean, $imei1, $imei2, $kusy, $jmeno, $text, $typ, $faktura, $pobocka) {
        return $this->db->dotaz('INSERT INTO zarizeni (ean, imei1, imei2, kusy, jmeno, text, typ, faktura, pobocka) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', array($ean, $imei1, $imei2, $kusy, $jmeno, $text, $typ, $faktura, $pobocka), 1);
    }

    public function vratSumuImei($ean, $imei, $pobocka) {
        return $this->db->dotazJeden('SELECT SUM(kusy) as kusy FROM zarizeni WHERE ean = ? AND pobocka = ? AND (imei1 = ? OR imei2 = ?)', array($ean, $pobocka->getId(), $imei, $imei));
    }

    public function vratVsechnyZaznamyEan($ean, $pobocka) {
        return $this->db->dotazVsechnyObjekty('SELECT A.id, A.ean, A.imei1, A.imei2, A.kusy, A.text, A.typ, A.faktura, A.datum, B.jmeno FROM zarizeni as A, uzivatele as B WHERE ean = ? AND A.pobocka = ? AND B.id = A.jmeno', 'Zaznam', array($ean, $pobocka));
    }

    public function vratZaznamy($pocet, $pobocka) {
        return $this->db->dotazVsechnyObjekty('select A.id, A.ean, A.imei1, A.imei2, A.kusy, C.jmeno, A.text, A.typ, A.faktura, A.datum, B.zbozi, B.model, B.popis
from (select * from zarizeni where pobocka = ? order by datum desc limit ' . $pocet . ')
as A left join sap as B on A.ean = B.ean left join uzivatele as C on A.jmeno = C.id  order by datum desc', 'Zaznam', array($pobocka->getId()));
    }

    public function updatniFakturu($id, $cislo){
        return $this->db->dotaz('update zarizeni set faktura = ? where id = ?', array($cislo, $id));
    }

    /**
     * @param $id
     * @return \app\modely\Zaznam
     */
    public function vratZaznam($id){
        return $this->db->dotazObjekt('select * from zarizeni where id = ?', 'Zaznam', array($id));
    }

    /**
     * @param $ean
     * @param $pobocka
     * @return \app\modely\Zaznam
     */
    public function vratNevystavenoEan($ean, $pobocka){
        return $this->db->dotazObjekt('select * from nevystavene where ean = ? and pobocka = ?', 'Zaznam', array($ean, $pobocka));
    }

    /**
     * @param $ora
     * @param $pobocka
     * @return \app\modely\Zaznam
     */
    public function vratNevystavenoOra($ora, $pobocka){
        return $this->db->dotazObjekt('select * from nevystavene where ora = ? and pobocka = ?', 'Zaznam', array($ora, $pobocka));
    }

    public function updateKusyNevystaveno($id, $kusy, $sap = 0){
        return $this->db->dotaz('update nevystavene set kusy = ?, sap = ? where id = ?', array($kusy, $sap, $id));
    }

    public function pridejNevystaveno($ean, $ora, $kusy, $pobocka){
        return $this->db->dotaz('insert into nevystavene (ora, ean, kusy, pobocka) values(?, ?, ?, ?)', array($ora, $ean, $kusy, $pobocka));
    }

    public function vratVsehnyNevystaveno($pobocka){
        return $this->db->dotazVsechnyObjekty('select A.id, A.ean, A.kusy, A.pobocka, A.datum, B.zbozi, B.model, B.popis from (select * from nevystavene where pobocka = ? and sap = 0 and kusy > 0) as A left join sap as B on A.ean = B.ean ', 'Zaznam', array($pobocka));
    }

    public function zmenSapVystaveno($pobocka){
        return $this->db->dotaz('update nevystavene set sap = 1 where pobocka = ? and kusy > 0 and sap = 0', array($pobocka));
    }

    public function smazNevystaveno($id){
        return $this->db->dotaz('delete from nevystavene where id = ?', array($id));
    }

    public function vratCerstveVystaveno($pobocka){
        return $this->db->dotazVsechnyObjekty('select * from nevystavene where sap = 0 and pobocka = ?', 'Zaznam', array($pobocka));
    }
}