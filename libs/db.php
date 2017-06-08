<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 25.04.2017
 * Time: 13:30
 */

namespace libs;

use PDO;
use PDOException;

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

class db {

    private $spojeni = null;

    public function __construct($options = null) {
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
//        try {
//            $opts = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION);
            if (!$this->spojeni) {
                $this->spojeni = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, $options);
            }
//        }catch(PDOException $e){
//            print_r($e);
//        }
    }

    public function dotazJeden($dotaz, $parametry = array()) {
        $navrat = $this->spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->fetch();
    }

    public function dotazId($dotaz, $parametry = array()) {
        $navrat = $this->spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $this->spojeni->lastInsertId();
    }

    public function dotazVsechny($dotaz, $parametry = array()) {
        $navrat = $this->spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->fetchAll();
    }

    public function dotazSamotny($dotaz, $parametry = array()) {
        $vysledek = $this->dotazJeden($dotaz, $parametry);
        return $vysledek[0];
    }

    public function dotaz($dotaz, $parametry = array()) {
        $navrat = $this->spojeni->prepare($dotaz);
        $navrat->execute($parametry);
        return $navrat->rowCount();
    }

    public function dotazVsechnyObjekty($dotaz, $typ, $parametry = array()) {
        $stmt = $this->spojeni->prepare($dotaz);
        $stmt->execute($parametry);
        var_dump($stmt->fetchAll());
        var_dump($parametry);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'app\\modely\\' . $typ);
        return $stmt->fetchAll();
    }

    public function dotazObjekt($dotaz, $typ, $parametry = array()) {
        $stmt = $this->spojeni->prepare($dotaz);
        $a = $stmt->execute($parametry);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'app\\modely\\' . $typ);
        return $stmt->fetch();
    }
}