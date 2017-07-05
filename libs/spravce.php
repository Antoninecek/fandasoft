<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 26.04.2017
 * Time: 12:20
 */

namespace libs;

class Spravce {

    protected $db = null;

    public function __construct($spojeni) {
        $this->db = new db($spojeni);
    }
}