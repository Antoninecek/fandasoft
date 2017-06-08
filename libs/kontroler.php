<?php

namespace libs;


class Kontroler {

    /**
     * @var Pohled
     */
    public $sablona = null;

    protected function setSablonu($sablona) {
        $this->sablona = new Pohled($sablona);
    }



}