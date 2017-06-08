<?php

namespace app\kontrolery;

use libs\Kontroler;
use libs\Pohled;

class Home extends Kontroler {

    public function __construct() {
        $this->setSablonu('app/sablony/vychozi');
    }

    public function index(){
        $this->sablona->set('titulek', 'hello world');
        $content = new Pohled('app/pohledy/uvodni');
        $this->sablona->set('content', $content->rendruj());
        echo $this->sablona->rendruj();
    }


}
