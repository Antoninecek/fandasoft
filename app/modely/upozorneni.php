<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 25.04.2017
 * Time: 18:07
 */

namespace app\modely;


class upozorneni {

    private $typ;
    private $zprava;

    public function __construct($typ, $zprava) {
        $this->typ = $typ;
        $this->zprava = $zprava;
    }

    public function getTyp() {
        return $this->typ;
    }

    public function getZprava() {
        return $this->zprava;
    }
}