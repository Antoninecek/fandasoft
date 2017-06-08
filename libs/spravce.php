<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 26.04.2017
 * Time: 12:20
 */

namespace libs;
use PDOException;

class spravce {

    protected $db = null;

    public function __construct() {
//        try {
            $this->db = new db();
//        } catch (PDOException $e){
//            print_r($e);
//            echo "aaaa";
//        }
    }

//    public function getSpojeni(){
//        return $this->db;
//    }

}