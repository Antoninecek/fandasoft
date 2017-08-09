<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 01.06.2017
 * Time: 2:08
 */

namespace app\modely;


class Zbozi implements \JsonSerializable {
    private $zbozi, $model, $ean, $popis, $kusy, $dualsim;

    /**
     * zbozi constructor.
     * @param null $zbozi
     * @param null $model
     * @param null $ean
     * @param null $popis
     * @param null $kusy
     * @param null $dualsim
     */
    public function __construct($zbozi = null, $model = null, $ean = null, $popis = null, $kusy = null, $dualsim = null) {
        if ($zbozi != null) {
            $this->zbozi = $zbozi;
            $this->model = $model;
            $this->ean = $ean;
            $this->popis = $popis;
            $this->kusy = $kusy;
            $this->dualsim = $dualsim;
        }
    }

    /**
     * @return null
     */
    public function getDualsim() {
        return $this->dualsim;
    }

    /**
     * @param null $dualsim
     */
    public function setDualsim($dualsim) {
        $this->dualsim = $dualsim;
    }

    /**
     * @return mixed
     */
    public function getZbozi() {
        return $this->zbozi;
    }

    /**
     * @param mixed $zbozi
     */
    public function setZbozi($zbozi) {
        $this->zbozi = $zbozi;
    }

    /**
     * @return mixed
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * @param mixed $model
     */
    public function setModel($model) {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function getEan() {
        return $this->ean;
    }

    /**
     * @param mixed $ean
     */
    public function setEan($ean) {
        $this->ean = $ean;
    }

    /**
     * @return mixed
     */
    public function getPopis() {
        return $this->popis;
    }

    /**
     * @param mixed $popis
     */
    public function setPopis($popis) {
        $this->popis = $popis;
    }

    /**
     * @return mixed
     */
    public function getKusy() {
        return $this->kusy;
    }

    /**
     * @param mixed $kusy
     */
    public function setKusy($kusy) {
        $this->kusy = $kusy;
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