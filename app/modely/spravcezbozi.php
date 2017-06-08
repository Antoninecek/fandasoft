<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 01.06.2017
 * Time: 2:12
 */

namespace app\modely;


use libs\spravce;

class spravcezbozi extends spravce {

    public function vratZboziEan($ean) {
        return $this->db->dotazObjekt('SELECT * FROM sap WHERE ean = ?', 'zbozi', array($ean));
    }

    public function vratZboziOra($ora) {
        return $this->db->dotazObjekt('SELECT * FROM sap WHERE zbozi = ?', 'zbozi', array($ora));
    }

    public function zjistiDualsim($ora) {
        return $this->db->dotazJeden('SELECT dualsim FROM dualsim WHERE zbozi = ?', array($ora));
    }

}