<?php

namespace libs;

require_once('htmlpurifier/library/HTMLPurifier.auto.php');

class Purifier {

    private $instance = null;

    public function __construct() {
        $this->instance =  new \HTMLPurifier;
    }


    public function act($action, $param){
        return $this->instance->{$action}($param);
    }
}