<?php

namespace libs;

class Pohled {

    protected $_file;
    protected $_data = array();

    public function __construct($sablona, array $data = null) {
        $this->_file = $sablona . ".php";

        if(!is_null($data)){
            array_merge($data, $this->_data);
        }
    }

    public function set($klic, $hodnota = NULL) {
        $this->_data[$klic] = $hodnota;
        return $this;
    }

    public function rendruj(){
        extract($this->_data);
        //start output buffering
        ob_start();
        try {
            require_once $this->_file;
        }
        catch (Exception $e) {
            // pokud se to nepovede, smazeme bufer
            ob_end_clean();
            // Re-throw the exception
            throw $e;
        }

        // vrati obsah bufferu jako retezec
        return ob_get_clean();
    }


}