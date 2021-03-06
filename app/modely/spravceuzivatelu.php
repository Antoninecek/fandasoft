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
        return $this->db->dotazJeden('SELECT id FROM uzivatele WHERE jmeno = ?', array($jmeno));
    }

    /**
     * @param $id
     * @return Uzivatel
     */
    public function vratUzivatele($id) {
        return $this->db->dotazObjekt('SELECT * FROM uzivatele WHERE id = ?', 'Uzivatel', array($id));
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
        return $this->db->dotazObjekt('SELECT * FROM uzivatele WHERE BINARY heslo = ? AND pobocka = ?', 'Uzivatel', array($heslo, $pobocka->getIdPobocka()));

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
     * @param $oscislo
     * @return bool
     */
    public function jeValidniHeslo($oscislo, $heslo){
        return preg_match("/^" . $oscislo . "[0-9a-zA-Z]+/", $heslo) ? true : false;
    }

    /**
     * @param $oscislo
     * @param $jmeno
     * @param $heslo
     * @param $email
     * @param $pobocka
     * @return \libs\default
     */
    public function pridejUzivatele($oscislo, $jmeno, $heslo, $email, $pobocka){
        return $this->db->dotaz('insert into uzivatele (oscislo, jmeno, heslo, email, pobocka) values (?, ?, ?, ?, ?)', array($oscislo, $jmeno, $heslo, $email, $pobocka));
    }

    public function zjistiUnikatnostOscisla($oscislo){
        return $this->db->dotazObjekt('select * from uzivatele where oscislo = ?', 'Uzivatel', array($oscislo));
    }

    public function vratAktivniUzivatele($pobocka){
        return $this->db->dotazVsechnyObjekty('select * from uzivatele where pobocka = ?', 'Uzivatel', array($pobocka));
    }

    public function zmenAdmin($id, $admin){
        return $this->db->dotaz('update uzivatele set admin = ? where id = ?', array($admin, $id));
    }

    public function vratAdmin($id){
        return $this->db->dotazJeden('select admin from uzivatele where id = ?', array($id));
    }

}