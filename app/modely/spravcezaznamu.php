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

    /**
     * @param $ean
     * @return Zbozi
     */
    public function vratZboziEan($ean) {
        return $this->db->dotazObjekt('select * from sap where ean = ?', 'Zbozi', array($ean));
    }

    /**
     * @param $ora
     * @return Zbozi
     */
    public function vratZboziOra($ora) {
        return $this->db->dotazObjekt('select * from sap where zbozi = ?', 'Zbozi', array($ora));
    }

    public function vratPosledniPrijem($ean, $imei, $pobocka) {
        return $this->db->dotazObjekt('SELECT * FROM `zarizeni` where ean = ? and kusy > 0 and pobocka = ? and (imei1 = ? or imei2 = ?) ORDER BY `datum` DESC', 'Zaznam', array($ean, $pobocka->getId(), $imei, $imei));
    }

    public function pridejZaznam($ean, $imei1, $imei2, $kusy, $jmeno, $text, $typ, $faktura, $pobocka) {
        return $this->db->dotaz('INSERT INTO zarizeni (ean, imei1, imei2, kusy, jmeno, text, typ, faktura, pobocka) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)', array($ean, $imei1, $imei2, $kusy, $jmeno, $text, $typ, $faktura, $pobocka), 1);
    }

    public function vratSumuImei($ean, $imei, $pobocka) {
        return $this->db->dotazJeden('SELECT SUM(kusy) as kusy FROM zarizeni WHERE ean = ? AND pobocka = ? AND (imei1 = ? OR imei2 = ?)', array($ean, $pobocka->getId(), $imei, $imei));
    }

//    public function vratVsechnyZaznamyEan($ean, $pobocka) {
//        return $this->db->dotazVsechnyObjekty('SELECT A.id, A.ean, A.imei1, A.imei2, A.kusy, A.text, A.typ, A.faktura, A.datum, B.jmeno FROM zarizeni as A, uzivatele as B WHERE ean = ? AND A.pobocka = ? AND B.id = A.jmeno', 'Zaznam', array($ean, $pobocka));
//    }

    public function vratVsechnyZaznamyEan($ean, $pobocka){
        return $this->db->dotazVsechnyObjekty('select * from (select * from zarizeni where ean = ? and pobocka = ?) as A left join uzivatele as B on A.jmeno = B.id', 'Zaznam', array($ean, $pobocka));
    }

    public function vratZaznamy($pocet, $pobocka) {
        return $this->db->dotazVsechnyObjekty('select A.id, A.ean, A.imei1, A.imei2, A.kusy, C.jmeno, A.text, A.typ, A.faktura, A.datum, B.zbozi, B.model, B.popis
from (select * from zarizeni where pobocka = ? order by datum desc limit ' . $pocet . ')
as A left join sap as B on A.ean = B.ean left join uzivatele as C on A.jmeno = C.id  order by datum desc', 'Zaznam', array($pobocka->getId()));
    }

    public function updatniFakturu($id, $cislo) {
        return $this->db->dotaz('update zarizeni set faktura = ? where id = ?', array($cislo, $id));
    }

    /**
     * @param $id
     * @return \app\modely\Zaznam
     */
    public function vratZaznam($id) {
        return $this->db->dotazObjekt('select * from zarizeni where id = ?', 'Zaznam', array($id));
    }

    /**
     * @param $ean
     * @param $pobocka
     * @return \app\modely\Zaznam
     */
    public function vratNevystavenoEan($ean, $pobocka) {
        return $this->db->dotazObjekt('select * from nevystavene where ean = ? and pobocka = ?', 'Zaznam', array($ean, $pobocka));
    }

    /**
     * @param $ora
     * @param $pobocka
     * @return \app\modely\Zaznam
     */
    public function vratNevystavenoOra($ora, $pobocka) {
        return $this->db->dotazObjekt('select * from nevystavene where ora = ? and pobocka = ?', 'Zaznam', array($ora, $pobocka));
    }

    public function updateKusyNevystaveno($id, $kusy) {
        return $this->db->dotaz('update nevystavene set kusy = ? where id = ?', array($kusy, $id));
    }

    public function pridejNevystaveno($ora, $kusy, $pobocka) {
        return $this->db->dotaz('insert into nevystavene (ora, kusy, pobocka) values(?, ?, ?)', array($ora, $kusy, $pobocka));
    }

    public function zmenPriznakNevystaveno($priznak, $id) {
        return $this->db->dotaz('update nevystavene set sap = ? where id = ?', array($priznak, $id));
    }

    public function vratVsehnyNevystaveno($pobocka) {
        return $this->db->dotazVsechnyObjekty('select A.id, A.ean, A.ora, A.kusy, A.pobocka, A.datum, B.zbozi, B.model, B.popis from (select * from nevystavene where pobocka = ? and sap = 0) as A left join sap as B on A.ean = B.ean ', 'Zaznam', array($pobocka));
    }

    public function zmenSapVystaveno($pobocka, $priznak) {
        return $this->db->dotaz('update nevystavene set sap = ? where pobocka = ? and sap = 0', array($priznak, $pobocka));
    }

    public function smazNevystaveno($id) {
        return $this->db->dotaz('delete from nevystavene where id = ?', array($id));
    }

    public function vratCerstveVystaveno($pobocka) {
        return $this->db->dotazVsechnyObjekty('select * from nevystavene where sap = 0 and pobocka = ?', 'Zaznam', array($pobocka));
    }

    public function vratVsechnyZaznamyNevystaveno($pobocka) {
        return $this->db->dotazVsechnyObjekty('select NS.ora, NS.ean, NS.nevystkusy as nevystavkusy, NS.sap as priznak, NS.model, NS.popis, NS.kategorie, Z.zarkusy from (select N.ora, S.ean, N.kusy as nevystkusy, N.sap, S.model, S.popis, S.kategorie from (select * from nevystavene where pobocka = 1) as N left join sap as S on N.ora = S.zbozi) as NS left join (select ean, sum(kusy) as zarkusy from zarizeni where pobocka = 1 group by ean) as Z on Z.ean = NS.ean union select S.zbozi as ora, Z.ean, null as nevystkusy, null as sap, S.model, S.popis, S.kategorie, Z.kusy as zarkusy from (select ean, sum(kusy) as kusy from zarizeni where pobocka = 1 and ean not in(select distinct sap.ean from nevystavene, sap where nevystavene.ora = sap.zbozi) group by ean) as Z left join sap as S on Z.ean = S.ean', 'Zaznam', array($pobocka));
    }

}