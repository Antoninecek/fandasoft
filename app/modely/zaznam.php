<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 26.04.2017
 * Time: 12:14
 */

namespace app\modely;


class ukol {
    private $id, $ean, $imei, $imei1, $kusy, $jmeno, $text, $typ, $faktura, $datum, $pobocka;

    /**
     * ukol constructor.
     * @param $id
     * @param $ean
     * @param $imei
     * @param $imei1
     * @param $kusy
     * @param $jmeno
     * @param $text
     * @param $typ
     * @param $faktura
     * @param $datum
     * @param $pobocka
     */
    public function __construct($id = null, $ean = null, $imei = null, $imei1 = null, $kusy = null, $jmeno = null, $text = null, $typ = null, $faktura = null, $datum = null, $pobocka = null) {
        if ($id != null) {
            $this->id = $id;
            $this->ean = $ean;
            $this->imei = $imei;
            $this->imei1 = $imei1;
            $this->kusy = $kusy;
            $this->jmeno = $jmeno;
            $this->text = $text;
            $this->typ = $typ;
            $this->faktura = $faktura;
            $this->datum = $datum;
            $this->pobocka = $pobocka;
        }
    }

    /**
     * @return null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return null
     */
    public function getEan() {
        return $this->ean;
    }

    /**
     * @param null $ean
     */
    public function setEan($ean) {
        $this->ean = $ean;
    }

    /**
     * @return null
     */
    public function getImei() {
        return $this->imei;
    }

    /**
     * @param null $imei
     */
    public function setImei($imei) {
        $this->imei = $imei;
    }

    /**
     * @return null
     */
    public function getImei1() {
        return $this->imei1;
    }

    /**
     * @param null $imei1
     */
    public function setImei1($imei1) {
        $this->imei1 = $imei1;
    }

    /**
     * @return null
     */
    public function getKusy() {
        return $this->kusy;
    }

    /**
     * @param null $kusy
     */
    public function setKusy($kusy) {
        $this->kusy = $kusy;
    }

    /**
     * @return null
     */
    public function getJmeno() {
        return $this->jmeno;
    }

    /**
     * @param null $jmeno
     */
    public function setJmeno($jmeno) {
        $this->jmeno = $jmeno;
    }

    /**
     * @return null
     */
    public function getText() {
        return $this->text;
    }

    /**
     * @param null $text
     */
    public function setText($text) {
        $this->text = $text;
    }

    /**
     * @return null
     */
    public function getTyp() {
        return $this->typ;
    }

    /**
     * @param null $typ
     */
    public function setTyp($typ) {
        $this->typ = $typ;
    }

    /**
     * @return null
     */
    public function getFaktura() {
        return $this->faktura;
    }

    /**
     * @param null $faktura
     */
    public function setFaktura($faktura) {
        $this->faktura = $faktura;
    }

    /**
     * @return null
     */
    public function getDatum() {
        return $this->datum;
    }

    /**
     * @param null $datum
     */
    public function setDatum($datum) {
        $this->datum = $datum;
    }

    /**
     * @return null
     */
    public function getPobocka() {
        return $this->pobocka;
    }

    /**
     * @param null $pobocka
     */
    public function setPobocka($pobocka) {
        $this->pobocka = $pobocka;
    }





}