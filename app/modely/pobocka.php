<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 23.05.2017
 * Time: 18:29
 */

namespace app\modely;

use JsonSerializable;


class pobocka implements JsonSerializable {

    private $id, $id_pobocka, $nazev, $heslo, $mesto;

    public function __construct($id = null, $id_pobocka = null, $nazev = null, $heslo = null, $mesto = null) {
        if ($id != null) {
            $this->id = $id;
            $this->id_pobocka = $id_pobocka;
            $this->nazev = $nazev;
            $this->mesto = $mesto;
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
     * @return mixed
     */
    public function getNazev() {
        return $this->nazev;
    }

    /**
     * @return null
     */
    public function getHeslo() {
        return $this->heslo;
    }

    /**
     * @return mixed
     */
    public function getMesto() {
        return $this->mesto;
    }

    /**
     * @return mixed
     */
    public function getIdPobocka() {
        return $this->id_pobocka;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize() {
        return get_object_vars($this);
    }
}