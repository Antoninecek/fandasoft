<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 23.05.2017
 * Time: 17:30
 */

namespace app\modely;

use libs\Spravce;

class spravcepobocek extends Spravce {


    /**
     * @param $jmeno
     * @param $heslo
     * @return pobocka
     */
    public function vratPobocku($jmeno, $heslo){
        return $this->db->dotazObjekt('SELECT * FROM pobocky WHERE id = :jmeno AND heslo = :heslo', 'pobocka', array('jmeno' => $jmeno, 'heslo' => $heslo));
    }

    public function vratVsechnyPobocky(){
        return $this->db->dotazVsechnyObjekty('SELECT id, id_pobocka, mesto, nazev FROM pobocky', 'pobocka');
    }
}