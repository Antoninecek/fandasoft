<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 25.04.2017
 * Time: 14:20
 */

namespace app\modely;


class Uzivatel {

    private $id, $oscislo, $jmeno, $heslo, $email, $aktivni, $admin, $datum, $pobocka;

    /**
     * uzivatel constructor.
     * @param $id
     * @param $oscislo
     * @param $jmeno
     * @param $heslo
     * @param $email
     * @param $aktivni
     * @param $admin
     * @param $datum
     * @param $pobocka
     */
    public function __construct($id = null, $oscislo = null, $jmeno = null, $heslo = null, $email = null, $aktivni = null, $admin = null, $datum = null, $pobocka = null) {
        if ($id != null) {
            $this->id = $id;
            $this->oscislo = $oscislo;
            $this->jmeno = $jmeno;
            $this->email = $email;
            $this->aktivni = $aktivni;
            $this->admin = $admin;
            $this->datum = $datum;
            $this->pobocka = $pobocka;
        }
        $this->heslo = null;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getOscislo() {
        return $this->oscislo;
    }

    /**
     * @param mixed $oscislo
     */
    public function setOscislo($oscislo) {
        $this->oscislo = $oscislo;
    }

    /**
     * @return mixed
     */
    public function getJmeno() {
        return $this->jmeno;
    }

    /**
     * @param mixed $jmeno
     */
    public function setJmeno($jmeno) {
        $this->jmeno = $jmeno;
    }

    /**
     * @return mixed
     */
    public function getHeslo() {
        return $this->heslo;
    }

    /**
     * @param mixed $heslo
     */
    public function setHeslo($heslo) {
        $this->heslo = $heslo;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getAktivni() {
        return $this->aktivni;
    }

    /**
     * @param mixed $aktivni
     */
    public function setAktivni($aktivni) {
        $this->aktivni = $aktivni;
    }

    /**
     * @return mixed
     */
    public function getAdmin() {
        return $this->admin;
    }

    /**
     * @param mixed $admin
     */
    public function setAdmin($admin) {
        $this->admin = $admin;
    }

    /**
     * @return mixed
     */
    public function getDatum() {
        return $this->datum;
    }

    /**
     * @param mixed $datum
     */
    public function setDatum($datum) {
        $this->datum = $datum;
    }

    /**
     * @return mixed
     */
    public function getPobocka() {
        return $this->pobocka;
    }

    /**
     * @param mixed $pobocka
     */
    public function setPobocka($pobocka) {
        $this->pobocka = $pobocka;
    }

}