<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 25.04.2017
 * Time: 13:16
 */

namespace app\modely;

//use app\kontrolery\Uzivatel;
use libs\Spravce;

class SpravceUzivatelu extends Spravce{

    /**
     * @param $jmeno
     * @return mixed
     */
    public function vratId($jmeno) {
        return $this->db->dotazJeden('SELECT id FROM Uzivatele WHERE jmeno = ?', array($jmeno));
    }

    /**
     * @param $id
     * @return Uzivatel
     */
    public function vratUzivatele($id) {
        return $this->db->dotazObjekt('SELECT * FROM Uzivatele WHERE id = ?', 'Uzivatel', array($id));
    }

    /**
     * @param $oscislo
     * @param $heslo
     * @param $pobocka
     * @return Uzivatel
     */
    public function overUzivatele($oscislo, $heslo, $pobocka) {
        return $this->db->dotazObjekt('SELECT * FROM uzivatele WHERE BINARY oscislo = BINARY ? AND BINARY heslo = BINARY ? AND pobocka = ?', 'Uzivatel', array($oscislo, $heslo, $pobocka));
    }

    /**
     * @param array $udaje id, heslo
     * @return Uzivatel
     */
    public function overUzivateleId($udaje) {
        return $this->db->dotazObjekt('SELECT * FROM uzivatele WHERE BINARY id = BINARY ? AND BINARY heslo = BINARY ?', 'Uzivatel', array($udaje['id'], $udaje['heslo']));
    }

    /**
     * @param $heslo
     * @param $pobocka pobocka
     * @return Uzivatel
     */
    public function overHeslo($heslo, $pobocka){
        return $this->db->dotazObjekt('SELECT * FROM uzivatele WHERE BINARY heslo = BINARY ? AND pobocka = ?', 'Uzivatel', array($heslo, $pobocka->getIdPobocka()));
    }

    /**
     * insert
     * @param $udaje
     * @return string
     */
    public function zapisUzivatele($udaje) {
        return $this->db->dotazId('INSERT INTO uzivatele (jmeno, heslo, opravneni) VALUES (?, ?, ?)', array($udaje['jmeno'], $udaje['heslo'], $udaje['opravneni']));
    }

    public function zmenHeslo($id, $heslo){
        return $this->db->dotaz('update uzivatele set heslo = ? where id = ?', array($heslo, $id));
    }

    /**
     * overeni prihlaseneho Uzivatele v session
     * @return bool
     */
    public function jePrihlasen(){
//        var_dump($_SESSION['Uzivatel']);
        return !empty($_SESSION['uzivatel']);
    }

    /**
     * validace noveho hesla ve spravnem tvaru s nalezitostmi
     * @param $heslo
     * @return bool
     */
    public function jeValidniHeslo($heslo){
        return true;
    }

    /**
     * @param $oscislo
     * @param $jmeno
     * @param $heslo
     * @param $email
     * @return Uzivatel
     */
    public function pridejUzivatele($oscislo, $jmeno, $heslo, $email){
        return $this->db->dotazObjekt('insert into uzivatele (oscislo, jmeno, heslo, email) values (?, ?, ?, ?)', 'Uzivatel', array($oscislo, $jmeno, $heslo, $email));
    }

}