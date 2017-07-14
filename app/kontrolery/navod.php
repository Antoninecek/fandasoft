<?php
/**
 * Created by PhpStorm.
 * User: František
 * Date: 07.07.2017
 * Time: 0:27
 */

namespace app\kontrolery;


use libs\Kontroler;
use libs\Pohled;

class navod extends Kontroler {

    public function __construct() {
        $this->setSablonu('app/sablony/vychozi');
    }

    public function index(){
        $this->sablona->set('titulek', 'navod');
        $content = new Pohled('app/pohledy/navod');
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }
}