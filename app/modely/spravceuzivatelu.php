<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 25.04.2017
 * Time: 13:16
 */

namespace app\modely;

use libs\Spravce;
use app\modely\pobocka;

class spravceUzivatelu extends Spravce{

    /**
     * @param $jmeno
     * @return mixed
     */
    public function vratId($jmeno) {
        return $this->db->dotazJeden('SELECT id FROM uzivatele WHERE jmeno = ?', array($jmeno));
    }

    /**
     * @param $id
     * @return uzivatel
     */
    public function vratUzivatele($id) {
        return $this->db->dotazObjekt('SELECT * FROM uzivatele WHERE id = ?', 'uzivatel', array($id));
    }

    /**
     * @param $udaje - jmeno, heslo
     * @return uzivatel
     */
    public function overUzivatele($udaje) {
        return $this->db->dotazObjekt('SELECT * FROM uzivatele WHERE BINARY jmeno = BINARY ? AND BINARY heslo = BINARY ?', 'uzivatel', array($udaje['jmeno'], $udaje['heslo']));
    }

    /**
     * @param $heslo
     * @param $pobocka pobocka
     * @return uzivatel
     */
    public function overHeslo($heslo, $pobocka){
        return $this->db->dotazObjekt('SELECT * FROM uzivatele WHERE BINARY heslo = BINARY ? AND pobocka = ?', 'uzivatel', array($heslo, $pobocka->getIdPobocka()));
    }

    /**
     * insert
     * @param $udaje
     * @return string
     */
    public function zapisUzivatele($udaje) {
        return $this->db->dotazId('INSERT INTO uzivatele (jmeno, heslo, opravneni) VALUES (?, ?, ?)', array($udaje['jmeno'], $udaje['heslo'], $udaje['opravneni']));
    }
}